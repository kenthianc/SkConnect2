<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Member;
use App\Models\Event;
use App\Models\Attendance;
use Carbon\Carbon;

class OllamaService
{
    protected $baseUrl;
    protected $model;
    protected $timeout;

    public function __construct()
    {
        $this->baseUrl = config('services.ollama.url', 'http://localhost:11434');
        $this->model = config('services.ollama.model', 'llama3.1:8b');
        $this->timeout = 30; // seconds
    }

    /**
     * Ask Ollama AI a question with context
     */
    public function ask(string $query): string
    {
        try {
            // Build context from database (stats + specific member/event)
            $context = $this->buildContext($query);

            /*
            |--------------------------------------------------------------------------
            | DIRECT, ZERO-HALLUCINATION ANSWERS (from DB)
            |--------------------------------------------------------------------------
            | We answer very common queries directly from the database,
            | and only fall back to the AI when needed.
            |--------------------------------------------------------------------------
            */

            // If we have a member and the question is "who ... id" / "whose membership id"
            if (!empty($context['member_details']) && preg_match('/who|whose/i', $query)) {
                $m = $context['member_details'];

                return sprintf(
                    "The membership ID <strong>%s</strong> belongs to <strong>%s</strong> from Purok %s.",
                    $m['member_id'],
                    $m['full_name'],
                    $m['purok'] ?? 'N/A'
                );
            }

            // If we have a member and the question is about info/details
            if (!empty($context['member_details']) &&
                preg_match('/(info|information|details|tell me about|provide)/i', $query)) {

                $m = $context['member_details'];

                return sprintf(
                    "<strong>%s</strong> (Member ID: %s) is a %s-year-old %s from Purok %s.<br>" .
                    "Role: %s<br>" .
                    "Email: %s<br>" .
                    "Phone: %s<br>" .
                    "Joined on: %s<br>" .
                    "Status: %s",
                    $m['full_name'],
                    $m['member_id'],
                    $m['age'],
                    $m['gender'],
                    $m['purok'],
                    $m['role'],
                    $m['email'],
                    $m['phone'],
                    $m['date_joined'] ?? 'Unknown date',
                    $m['is_active'] ? 'Active' : 'Inactive'
                );
            }

            // If we have an event and the question is "when..."
            if (!empty($context['event_details']) && preg_match('/when/i', $query)) {
                $e = $context['event_details'];

                return sprintf(
                    "The event <strong>%s</strong> happened on <strong>%s</strong>.",
                    $e['title'],
                    $e['date'] ?? 'an unknown date'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | OTHERWISE: LET OLLAMA ANSWER USING FULL CONTEXT
            |--------------------------------------------------------------------------
            */

            // Prepare prompt
            $prompt = $this->buildPrompt($query, $context);

            // Call Ollama API
            $response = Http::timeout($this->timeout)
                ->post("{$this->baseUrl}/api/generate", [
                    'model'  => $this->model,
                    'prompt' => $prompt,
                    'stream' => false,
                ]);

            if ($response->successful()) {
                $result = $response->json();
                return $this->formatResponse($result['response'] ?? 'No response generated');
            }

            throw new \Exception('Ollama API request failed');
        } catch (\Exception $e) {
            Log::error('Ollama Service Error: ' . $e->getMessage());
            
            // Fallback to basic response
            return $this->getFallbackResponse($query);
        }
    }

    /**
     * Build context from database
     * - General statistics
     * - Specific member (by membership ID or name)
     * - Specific event ("when did X event happen?")
     */
    protected function buildContext(string $query): array
    {
        $context = [];

        /*
        |--------------------------------------------------------------------------
        | GENERAL STATS
        |--------------------------------------------------------------------------
        */

        // Members statistics
        // NOTE: You had Member::active() scope, we keep that.
        $context['total_members']   = Member::count();
        $context['active_members']  = method_exists(Member::class, 'active')
            ? Member::active()->count()
            : Member::where('is_active', true)->count();
        $context['officers']        = Member::where('role', 'Officer')->count();
        
        // Gender distribution
        $context['male_members']    = Member::where('gender', 'Male')->count();
        $context['female_members']  = Member::where('gender', 'Female')->count();
        $context['other_gender']    = Member::where('gender', 'Other')->count();

        // Purok distribution
        $context['purok_distribution'] = Member::select('purok')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('purok')
            ->orderByDesc('count')
            ->get()
            ->toArray();

        // Events statistics (keep your existing scopes)
        $context['total_events']     = Event::count();
        $context['upcoming_events']  = method_exists(Event::class, 'upcoming')
            ? Event::upcoming()->count()
            : Event::where('date', '>=', now())->count();
        $context['completed_events'] = method_exists(Event::class, 'completed')
            ? Event::completed()->count()
            : Event::where('date', '<', now())->count();

        // Attendance statistics
        $totalAttendance   = Attendance::count();
        $presentAttendance = Attendance::where('status', 'Present')->count();
        $context['attendance_rate'] = $totalAttendance > 0 
            ? round(($presentAttendance / $totalAttendance) * 100, 1) 
            : 0;

        // Recent events (you already use 'date' + 'status')
        $context['recent_events'] = Event::orderBy('date', 'desc')
            ->take(5)
            ->get(['id', 'title', 'date', 'status', 'location'])
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | QUERY-SPECIFIC CONTEXT: MEMBERS
        |--------------------------------------------------------------------------
        */

        // 1) Membership ID in the query: "Who owns membership ID 241015?"
        if (preg_match('/(member(ship)?\s*id|member id|id)\s*#?\s*([0-9A-Za-z\-]+)/i', $query, $matches)) {
            $memberId = trim($matches[3]);

            $member = Member::where('member_id', $memberId)->first();

            if ($member) {
                $context['member_query_type']  = 'by_id';
                $context['member_query_value'] = $memberId;

                $context['member_details'] = $this->mapMemberDetails($member);
            }
        }

        // 2) Name-based queries: "Provide me information about Glenn Quezon"
        if (!isset($context['member_details'])) {
            if (preg_match('/(who is|who\'s|information about|info about|details about|tell me about|provide me information about)\s+(.+)/i', $query, $matches)) {
                $namePart = trim($matches[2]);
                $namePart = preg_replace('/[?.!]+$/', '', $namePart); // remove trailing ?.! 

                if ($namePart !== '') {
                    $member = Member::query()
                        ->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$namePart}%"])
                        ->orWhereRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ?", ["%{$namePart}%"])
                        ->first();

                    if ($member) {
                        $context['member_query_type']  = 'by_name';
                        $context['member_query_value'] = $namePart;

                        $context['member_details'] = $this->mapMemberDetails($member);
                    }
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | QUERY-SPECIFIC CONTEXT: EVENTS
        |--------------------------------------------------------------------------
        | Example queries:
        |  - "When did the cleanup drive event happen?"
        |  - "When was the tree planting event?"
        |--------------------------------------------------------------------------
        */

        if (preg_match('/when did (.+?) (event|happen)/i', $query, $matches)) {
            $eventKeyword = trim($matches[1]);

            $event = Event::where('title', 'like', "%{$eventKeyword}%")
                ->orderBy('date', 'desc') // you already use 'date'
                ->first();

            if ($event) {
                $context['event_query_keyword'] = $eventKeyword;
                $context['event_details'] = $this->mapEventDetails($event);
            }
        }

        // Explicit "cleanup drive" shortcut
        if (!isset($context['event_details']) && preg_match('/cleanup\s*drive/i', $query)) {
            $event = Event::where('title', 'like', '%cleanup%')
                ->orderBy('date', 'desc')
                ->first();

            if ($event) {
                $context['event_query_keyword'] = 'cleanup drive';
                $context['event_details'] = $this->mapEventDetails($event);
            }
        }

        return $context;
    }

    /**
     * Map Member model to array for prompt/answers
     */
    protected function mapMemberDetails(Member $member): array
    {
        return [
            'id'               => $member->id,
            'member_id'        => $member->member_id,
            'full_name'        => trim(
                $member->first_name . ' ' .
                ($member->middle_name ? $member->middle_name . ' ' : '') .
                $member->last_name
            ),
            'first_name'       => $member->first_name,
            'middle_name'      => $member->middle_name,
            'last_name'        => $member->last_name,
            'email'            => $member->email,
            'phone'            => $member->phone,
            'birthdate'        => $member->birthdate
                ? Carbon::parse($member->birthdate)->format('F d, Y')
                : null,
            'age'              => $member->age,
            'gender'           => $member->gender,
            'purok'            => $member->purok,
            'address'          => $member->address,
            'guardian_name'    => $member->guardian_name,
            'guardian_contact' => $member->guardian_contact,
            'role'             => $member->role,
            'registered_via'   => $member->registered_via,
            'date_joined'      => $member->date_joined
                ? Carbon::parse($member->date_joined)->format('F d, Y')
                : null,
            'is_active'        => (bool) $member->is_active,
        ];
    }

    /**
     * Map Event model to array for prompt/answers
     */
    protected function mapEventDetails(Event $event): array
    {
        return [
            'id'       => $event->id,
            'title'    => $event->title,
            'date'     => $event->date
                ? Carbon::parse($event->date)->format('F d, Y')
                : null,
            'status'   => $event->status ?? null,
            'location' => $event->location ?? null,
        ];
    }

    /**
     * Build prompt for Ollama
     */
    protected function buildPrompt(string $query, array $context): string
    {
        $prompt  = "You are an AI assistant for SK Portal, a management system for Sangguniang Kabataan (Youth Council).\n";
        $prompt .= "You must answer based ONLY on the information provided below. If you don't see the data, say you don't know.\n\n";

        // GENERAL STATS
        $prompt .= "=== CURRENT STATISTICS ===\n";
        $prompt .= "- Total Members: {$context['total_members']}\n";
        $prompt .= "- Active Members: {$context['active_members']}\n";
        $prompt .= "- Officers: {$context['officers']}\n";
        $prompt .= "- Male Members: {$context['male_members']}\n";
        $prompt .= "- Female Members: {$context['female_members']}\n";
        $prompt .= "- Other Gender: {$context['other_gender']}\n";
        $prompt .= "- Total Events: {$context['total_events']}\n";
        $prompt .= "- Upcoming Events: {$context['upcoming_events']}\n";
        $prompt .= "- Completed Events: {$context['completed_events']}\n";
        $prompt .= "- Attendance Rate: {$context['attendance_rate']}%\n\n";

        // Purok Distribution
        if (!empty($context['purok_distribution'])) {
            $prompt .= "=== PUROK DISTRIBUTION ===\n";
            foreach ($context['purok_distribution'] as $purok) {
                $prompt .= "- Purok {$purok['purok']}: {$purok['count']} members\n";
            }
            $prompt .= "\n";
        }

        // Recent Events
        if (!empty($context['recent_events'])) {
            $prompt .= "=== RECENT EVENTS ===\n";
            foreach ($context['recent_events'] as $event) {
                $date = $event['date']
                    ? Carbon::parse($event['date'])->format('F d, Y')
                    : 'Unknown date';
                $status = $event['status'] ?? 'Unknown';
                $prompt .= "- {$event['title']} ({$date}) - Status: {$status}\n";
            }
            $prompt .= "\n";
        }

        // Specific Member Details
        if (!empty($context['member_details'])) {
            $m = $context['member_details'];

            $prompt .= "=== RELEVANT MEMBER DETAILS (FROM DATABASE) ===\n";
            $prompt .= "Member ID: {$m['member_id']}\n";
            $prompt .= "Full Name: {$m['full_name']}\n";
            $prompt .= "First Name: {$m['first_name']}\n";
            if (!empty($m['middle_name'])) {
                $prompt .= "Middle Name: {$m['middle_name']}\n";
            }
            $prompt .= "Last Name: {$m['last_name']}\n";
            $prompt .= "Email: {$m['email']}\n";
            $prompt .= "Phone: {$m['phone']}\n";
            if (!empty($m['birthdate'])) {
                $prompt .= "Birthdate: {$m['birthdate']}\n";
            }
            $prompt .= "Age: {$m['age']}\n";
            $prompt .= "Gender: {$m['gender']}\n";
            $prompt .= "Purok: {$m['purok']}\n";
            $prompt .= "Address: {$m['address']}\n";
            $prompt .= "Guardian Name: {$m['guardian_name']}\n";
            $prompt .= "Guardian Contact: {$m['guardian_contact']}\n";
            $prompt .= "Role: {$m['role']}\n";
            $prompt .= "Registered Via: {$m['registered_via']}\n";
            if (!empty($m['date_joined'])) {
                $prompt .= "Date Joined: {$m['date_joined']}\n";
            }
            $prompt .= "Is Active: " . ($m['is_active'] ? 'Yes' : 'No') . "\n\n";
        }

        // Specific Event Details
        if (!empty($context['event_details'])) {
            $e = $context['event_details'];

            $prompt .= "=== RELEVANT EVENT DETAILS (FROM DATABASE) ===\n";
            if (!empty($e['title'])) {
                $prompt .= "Title: {$e['title']}\n";
            }
            if (!empty($e['date'])) {
                $prompt .= "Date: {$e['date']}\n";
            }
            if (!empty($e['location'])) {
                $prompt .= "Location: {$e['location']}\n";
            }
            if (!empty($e['status'])) {
                $prompt .= "Status: {$e['status']}\n";
            }
            $prompt .= "\n";
        }

        // Final instructions & user question
        $prompt .= "=== USER QUESTION ===\n{$query}\n\n";
        $prompt .= "=== INSTRUCTIONS ===\n";
        $prompt .= "- Use the statistics and details above to answer.\n";
        $prompt .= "- Do NOT invent data that is not shown.\n";
        $prompt .= "- If the information is not available, say you don't know.\n";
        $prompt .= "- Keep your answer concise, clear, and friendly.\n";
        $prompt .= "- Format your response in HTML if needed (use <br> for line breaks, <strong> for emphasis).\n\n";
        $prompt .= "Answer:";

        return $prompt;
    }

    /**
     * Format AI response
     */
    protected function formatResponse(string $response): string
    {
        // Clean up response
        $response = trim($response);
        
        // Remove any "Answer:" prefix if present
        $response = preg_replace('/^Answer:\s*/i', '', $response);
        
        return $response;
    }

    /**
     * Fallback response when Ollama is not available
     */
    protected function getFallbackResponse(string $query): string
    {
        $stats = [
            'members'    => Member::count(),
            'events'     => Event::count(),
            'attendance' => Attendance::where('status', 'Present')->count(),
        ];

        return "I'm currently unable to process complex queries. Here's a quick summary:<br><br>" .
               "• <strong>{$stats['members']}</strong> total members<br>" .
               "• <strong>{$stats['events']}</strong> total events<br>" .
               "• <strong>{$stats['attendance']}</strong> recorded attendances<br><br>" .
               "Please try rephrasing your question or ask something simpler!";
    }

    /**
     * Check if Ollama is available
     */
    public function isAvailable(): bool
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/api/version");
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}

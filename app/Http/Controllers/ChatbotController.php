<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Event;
use App\Models\Attendance;
use App\Services\OllamaService;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    protected $ollamaService;

    public function __construct(OllamaService $ollamaService)
    {
        $this->ollamaService = $ollamaService;
    }

    /**
     * Process chatbot query
     */
    public function ask(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:500',
        ]);

        $query = strtolower($request->input('query'));

        // Try to answer directly without AI for simple queries
        $directAnswer = $this->tryDirectAnswer($query);
        
        if ($directAnswer) {
            return response()->json([
                'response' => $directAnswer,
                'source' => 'direct'
            ]);
        }

        // Use Ollama for complex queries
        try {
            $response = $this->ollamaService->ask($request->input('query'));
            
            return response()->json([
                'response' => $response,
                'source' => 'ollama'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'response' => 'Sorry, I encountered an error processing your request. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Try to answer simple queries directly
     */
    private function tryDirectAnswer($query)
    {
        // Total members
        if (str_contains($query, 'how many member') || str_contains($query, 'total member')) {
            $count = Member::count();
            return "We currently have <strong>{$count} registered members</strong> in our SK organization.";
        }

        // Active members
        if (str_contains($query, 'active member')) {
            $count = Member::active()->count();
            return "There are <strong>{$count} active members</strong> in the organization.";
        }

        // Most active purok
        if (str_contains($query, 'most active purok') || str_contains($query, 'purok') && str_contains($query, 'active')) {
            $purok = Member::select('purok')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('purok')
                ->orderByDesc('count')
                ->first();
            
            if ($purok) {
                return "<strong>{$purok->purok}</strong> is the most active purok with <strong>{$purok->count} members</strong>.";
            }
        }

        // Upcoming events
        if (str_contains($query, 'upcoming event') || str_contains($query, 'next event')) {
            $count = Event::upcoming()->count();
            $events = Event::upcoming()->take(3)->get();
            
            $response = "We have <strong>{$count} upcoming events</strong>:<br><br>";
            foreach ($events as $event) {
                $response .= "• <strong>{$event->title}</strong> on {$event->date->format('F d, Y')}<br>";
            }
            return $response;
        }

        // Attendance rate
        if (str_contains($query, 'attendance rate') || str_contains($query, 'attendance')) {
            $total = Attendance::count();
            $present = Attendance::where('status', 'Present')->count();
            $rate = $total > 0 ? round(($present / $total) * 100, 1) : 0;
            
            return "The overall attendance rate is <strong>{$rate}%</strong> across all events.";
        }

        // Perfect attendance
        if (str_contains($query, 'perfect attendance') || str_contains($query, 'attended all')) {
            $totalEvents = Event::completed()->count();
            
            if ($totalEvents > 0) {
                $perfectMembers = Member::whereHas('attendance', function($q) {
                    $q->where('status', 'Present');
                }, '=', $totalEvents)->get();
                
                if ($perfectMembers->count() > 0) {
                    $response = "<strong>{$perfectMembers->count()} members</strong> have perfect attendance:<br><br>";
                    foreach ($perfectMembers->take(10) as $member) {
                        $response .= "• {$member->name} ({$member->member_id})<br>";
                    }
                    return $response;
                } else {
                    return "No members have perfect attendance yet.";
                }
            }
            return "No completed events yet to calculate perfect attendance.";
        }

        // Officers
        if (str_contains($query, 'officer')) {
            $officers = Member::where('role', 'Officer')->get();
            
            if ($officers->count() > 0) {
                $response = "We have <strong>{$officers->count()} SK Officers</strong>:<br><br>";
                foreach ($officers as $officer) {
                    $response .= "• <strong>{$officer->name}</strong> - {$officer->purok}<br>";
                }
                return $response;
            }
            return "No officers registered yet.";
        }

        // Gender distribution
        if (str_contains($query, 'gender') || str_contains($query, 'male') || str_contains($query, 'female')) {
            $males = Member::where('gender', 'Male')->count();
            $females = Member::where('gender', 'Female')->count();
            $total = Member::count();
            
            return "Gender distribution:<br><br>
                    • <strong>Male:</strong> {$males} (" . ($total > 0 ? round(($males/$total)*100, 1) : 0) . "%)<br>
                    • <strong>Female:</strong> {$females} (" . ($total > 0 ? round(($females/$total)*100, 1) : 0) . "%)";
        }

        return null;
    }
}

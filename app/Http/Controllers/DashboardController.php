<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Event;
use App\Models\News;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display dashboard
     */
    public function index()
    {
        // Update event statuses
        Event::updateEventStatuses();

        // Statistics
        $totalMembers = Member::active()->count();
        $newMembersThisMonth = Member::whereMonth('created_at', Carbon::now()->month)
        ->whereYear('created_at', Carbon::now()->year)
        ->count();
        
        $totalEvents = Event::count();
        $upcomingEvents = Event::upcoming()->count();
        $completedEvents = Event::completed()->count();
        
        // Calculate attendance rate (average across all events)
        // Per-event rate: (present_count / target_participants) * 100
        $eventsForRate = Event::completed()
            ->withCount([
                'attendances as present_count' => function ($q) {
                    $q->where('status', 'Present');
                },
            ])
            ->get(['id', 'target_participants']);

        $attendanceRate = $eventsForRate->count() > 0
            ? round($eventsForRate->avg(function ($event) {
                $target = (int) ($event->target_participants ?? 0);
                if ($target <= 0) {
                    return 0;
                }

                return ($event->present_count / $target) * 100;
            }), 1)
            : 0;
        
        $totalNews = News::count();
        $activeNews = News::recent()->count();

        // Recent members (last 5)
        $recentMembers = Member::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Upcoming events (next 3)
        $upcomingEventsList = Event::upcoming()
            ->whereDate('date', '>=', Carbon::today())
            ->orderBy('date')
            ->orderBy('start_time')
            ->take(3)
            ->get();

        return view('dashboard.index', compact(
            'totalMembers',
            'newMembersThisMonth',
            'totalEvents',
            'upcomingEvents',
            'completedEvents',
            'attendanceRate',
            'totalNews',
            'activeNews',
            'recentMembers',
            'upcomingEventsList'
        ));
    }

    public function getMemberGrowthData()
    {
        // Get the number of members registered per month for the current year
        $data = DB::table('members')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', date('Y')) // Filter by current year
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month', 'asc')
            ->get();

        // Format the data into an array for JavaScript
        $months = [];
        $counts = [];
        foreach ($data as $item) {
            $months[] = date('F', mktime(0, 0, 0, $item->month, 10)); // Get month name
            $counts[] = $item->count;
        }

        return response()->json([
            'months' => $months,
            'counts' => $counts
        ]);
    }
}

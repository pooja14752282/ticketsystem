<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketSupportTeam as SupportTeam;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $user  = auth()->user();

        // ===============================
        // Determine scope: admin (su=1) sees everything,
        // support team (su=4) sees only their assigned tickets
        // ===============================
        $isSupport = $user && $user->su == 4;
        $member    = null;

        $baseQuery = function () use ($isSupport, &$member, $user) {
            $query = Ticket::query();

            if ($isSupport) {
                $member = SupportTeam::where('email', $user->email)->first();

                if ($member) {
                    $query->where('assigned_team_member_id', $member->id);
                } else {
                    // no matching support record — show nothing
                    $query->whereRaw('1 = 0');
                }
            }

            return $query;
        };

        // ===============================
        // Metric Cards
        // ===============================
        $totalTickets = (clone $baseQuery())->count();

        $openTickets = (clone $baseQuery())->where('status', 'open')->count();

        $completedTickets = (clone $baseQuery())->where('status', 'completed')->count();

        $overdueTickets = (clone $baseQuery())
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', $today)
            ->whereNotIn('status', ['completed', 'closed'])
            ->count();

        // ===============================
        // Status Breakdown
        // ===============================
        $statusCounts = (clone $baseQuery())
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $statusLabels = [
            'open',
            'in_progress',
            'on_hold',
            'completed',
            'closed'
        ];

        $statusData = [];

        foreach ($statusLabels as $status) {
            $statusData[] = $statusCounts[$status] ?? 0;
        }

        // ===============================
        // Priority Breakdown
        // ===============================
        $priorityCounts = (clone $baseQuery())
            ->selectRaw('priority, COUNT(*) as total')
            ->groupBy('priority')
            ->pluck('total', 'priority')
            ->toArray();

        $urgentCount = $priorityCounts['urgent'] ?? 0;
        $highCount   = $priorityCounts['High'] ?? 0;
        $mediumCount = $priorityCounts['Medium'] ?? 0;
        $lowCount    = $priorityCounts['Low'] ?? 0;

        // ===============================
        // Line Chart (Last 7 Days)
        // ===============================
        $lineLabels = [];
        $lineCreated = [];
        $lineCompleted = [];

        for ($i = 6; $i >= 0; $i--) {

            $date = Carbon::today()->subDays($i);

            $lineLabels[] = $date->format('d M');

            $lineCreated[] = (clone $baseQuery())
                ->whereDate('created_at', $date)
                ->count();

            $lineCompleted[] = (clone $baseQuery())
                ->where('status', 'completed')
                ->whereDate('updated_at', $date)
                ->count();
        }

        // ===============================
        // Top Categories
        // ===============================
        $topCategories = (clone $baseQuery())
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // ===============================
        // SLA Compliance
        // ===============================
        $totalWithDue = (clone $baseQuery())->whereNotNull('due_date')->count();

        $withinSla = (clone $baseQuery())
            ->whereNotNull('due_date')
            ->where(function ($query) use ($today) {

                $query->whereDate('due_date', '>=', $today)
                    ->orWhereIn('status', ['completed', 'closed']);

            })
            ->count();

        $slaPercent = $totalWithDue > 0
            ? round(($withinSla / $totalWithDue) * 100)
            : 100;

        // ===============================
        // Recent Tickets
        // ===============================
        $recentTickets = (clone $baseQuery())
            ->with([
                'createdBy',
                'assignedTo'
            ])
            ->latest()
            ->take(5)
            ->get();

        // ===============================
        // Sidebar Count
        // ===============================
        $inProgressCount = (clone $baseQuery())->where('status', 'in_progress')->count();

        return view('dashboard', compact(
            'totalTickets',
            'openTickets',
            'completedTickets',
            'overdueTickets',
            'statusCounts',
            'statusData',
            'statusLabels',
            'priorityCounts',
            'urgentCount',
            'highCount',
            'mediumCount',
            'lowCount',
            'lineLabels',
            'lineCreated',
            'lineCompleted',
            'topCategories',
            'slaPercent',
            'withinSla',
            'totalWithDue',
            'recentTickets',
            'inProgressCount',
            'isSupport',
            'member'
        ));
    }
}
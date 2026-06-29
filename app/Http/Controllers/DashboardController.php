<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // ===============================
        // Metric Cards
        // ===============================
        $totalTickets = Ticket::count();

        $openTickets = Ticket::where('status', 'open')->count();

        $completedTickets = Ticket::where('status', 'completed')->count();

        $overdueTickets = Ticket::whereNotNull('due_date')
            ->whereDate('due_date', '<', $today)
            ->whereNotIn('status', ['completed', 'closed'])
            ->count();

        // ===============================
        // Status Breakdown
        // ===============================
        $statusCounts = Ticket::selectRaw('status, COUNT(*) as total')
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
        $priorityCounts = Ticket::selectRaw('priority, COUNT(*) as total')
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

            $lineCreated[] = Ticket::whereDate('created_at', $date)->count();

            $lineCompleted[] = Ticket::where('status', 'completed')
                ->whereDate('updated_at', $date)
                ->count();
        }

        // ===============================
        // Top Categories
        // ===============================
        $topCategories = Ticket::selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // ===============================
        // SLA Compliance
        // ===============================
        $totalWithDue = Ticket::whereNotNull('due_date')->count();

        $withinSla = Ticket::whereNotNull('due_date')
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
        $recentTickets = Ticket::with([
                'createdBy',
                'assignedTo'
            ])
            ->latest()
            ->take(5)
            ->get();

        // ===============================
        // Sidebar Count
        // ===============================
        $inProgressCount = Ticket::where('status', 'in_progress')->count();

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
    'inProgressCount'
));
    }
}
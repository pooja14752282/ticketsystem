<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // ── METRIC CARDS ────────────────────────────────────────
        $totalTickets    = Ticket::count();
        $openTickets     = Ticket::where('status', 'open')->count();
        $completedTickets = Ticket::where('status', 'completed')->count();
        $overdueTickets  = Ticket::whereNotNull('due_date')
                                  ->where('due_date', '<', $now)
                                  ->whereNotIn('status', ['completed', 'closed'])
                                  ->count();

        // ── STATUS BREAKDOWN (for donut chart) ──────────────────
        $statusCounts = Ticket::selectRaw('status, count(*) as count')
                               ->groupBy('status')
                               ->pluck('count', 'status')
                               ->toArray();

        $statusLabels = ['open', 'in_progress', 'on_hold', 'completed', 'closed'];
        $statusData   = array_map(fn($s) => $statusCounts[$s] ?? 0, $statusLabels);

        // ── PRIORITY BREAKDOWN ───────────────────────────────────
        $priorityCounts = Ticket::selectRaw('priority, count(*) as count')
                                  ->groupBy('priority')
                                  ->pluck('count', 'priority')
                                  ->toArray();

        $urgentCount  = $priorityCounts['urgent']  ?? 0;
        $highCount    = $priorityCounts['high']     ?? 0;
        $mediumCount  = $priorityCounts['medium']   ?? 0;
        $lowCount     = $priorityCounts['low']      ?? 0;

        // ── LINE CHART — last 7 days ─────────────────────────────
        $days = collect(range(6, 0))->map(fn($i) => $now->copy()->subDays($i));

        $lineLabels   = $days->map(fn($d) => $d->format('d M'))->values()->toArray();

        $lineCreated  = $days->map(fn($d) =>
            Ticket::whereDate('created_at', $d->toDateString())->count()
        )->values()->toArray();

        $lineCompleted = $days->map(fn($d) =>
            Ticket::where('status', 'completed')
                  ->whereDate('updated_at', $d->toDateString())
                  ->count()
        )->values()->toArray();

        // ── TOP CATEGORIES ───────────────────────────────────────
        $topCategories = Ticket::selectRaw('category, count(*) as count')
                                ->groupBy('category')
                                ->orderByDesc('count')
                                ->limit(5)
                                ->get();

        // ── SLA COMPLIANCE ───────────────────────────────────────
        $totalWithDue   = Ticket::whereNotNull('due_date')->count();
        $withinSla      = Ticket::whereNotNull('due_date')
                                 ->where(function($q) use ($now) {
                                     $q->where('due_date', '>=', $now)
                                       ->orWhereIn('status', ['completed', 'closed']);
                                 })
                                 ->count();
        $slaPercent = $totalWithDue > 0 ? round(($withinSla / $totalWithDue) * 100) : 100;

        // ── RECENT TICKETS ───────────────────────────────────────
        $recentTickets = Ticket::with(['createdBy', 'assignedTo', ])
                                ->latest()
                                ->limit(5)
                                ->get();

        // ── IN-PROGRESS COUNT (for sidebar badge) ───────────────
        $inProgressCount = Ticket::where('status', 'in_progress')->count();

        return view('dashboard', compact(
            'totalTickets',
            'openTickets',
            'completedTickets',
            'overdueTickets',
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
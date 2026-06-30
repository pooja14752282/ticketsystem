@extends('layout')

@section('title', $isSupport ?? false ? 'My Dashboard' : 'Support Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')

<!-- METRIC CARDS -->
<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-icon blue"><i class="fas fa-layer-group"></i></div>
        <div class="metric-body">
            <div class="metric-value">{{ $totalTickets }}</div>
            <div class="metric-label">Total Tickets</div>
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-icon amber"><i class="fas fa-folder-open"></i></div>
        <div class="metric-body">
            <div class="metric-value">{{ $openTickets }}</div>
            <div class="metric-label">Open Tickets</div>
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="metric-body">
            <div class="metric-value">{{ $completedTickets }}</div>
            <div class="metric-label">Completed Tickets</div>
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-icon red"><i class="fas fa-exclamation-circle"></i></div>
        <div class="metric-body">
            <div class="metric-value">{{ $overdueTickets }}</div>
            <div class="metric-label">Overdue Tickets</div>
        </div>
    </div>
</div>

<!-- CHARTS ROW -->
<div class="charts-row">
    <!-- LINE CHART -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Tickets Over Time</div>
            <div class="chart-legend">
                <span class="legend-line blue-line"></span> Created
                <span class="legend-line green-line"></span> Completed
            </div>
            <div class="chip">Last 7 Days</div>
        </div>
        <div class="card-body">
            <div style="position:relative; height:300px;">
                <canvas id="lineChart"></canvas>
            </div>
        </div>
    </div>

    <!-- DONUT CHART -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Tickets by Status</div>
        </div>
        <div class="card-body">
            <div style="position:relative; height:180px; width:180px; margin:0 auto 16px;">
                <canvas id="donutChart"></canvas>
            </div>
            @php
                $total = array_sum($statusData) ?: 1;
                $statusConfig = [
                    'open'        => ['color' => '#2563eb', 'label' => 'Open'],
                    'in_progress' => ['color' => '#7c3aed', 'label' => 'In Progress'],
                    'on_hold'     => ['color' => '#f59e0b', 'label' => 'On Hold'],
                    'completed'   => ['color' => '#16a34a', 'label' => 'Completed'],
                    'closed'      => ['color' => '#94a3b8', 'label' => 'Closed'],
                ];
            @endphp
            <div class="status-legend">
                @foreach($statusConfig as $key => $cfg)
                    @php $cnt = $statusCounts[$key] ?? 0; @endphp
                    <div class="legend-item">
                        <div class="legend-left">
                            <div class="legend-dot" style="background:{{ $cfg['color'] }}"></div>
                            <span class="legend-name">{{ $cfg['label'] }}</span>
                        </div>
                        <span class="legend-right">{{ $cnt }} ({{ round($cnt/$total*100,1) }}%)</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- BOTTOM ROW -->
<div class="bottom-row">

    <!-- RECENT TICKETS (left, full height) -->
    <div class="card tickets-card">
        <div class="tbl-header">
            <div class="card-title">{{ $isSupport ?? false ? 'My Recent Tickets' : 'Recent Tickets' }}</div>
            <a href="{{ ($isSupport ?? false) ? route('support.tickets') : route('admin.tickets.index') }}" class="view-all-btn">View All</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Title</th>
                    <th>App Name</th>
                    <th>Created By</th>
                    <th>Assigned To</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Created On</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentTickets as $ticket)
                    <tr>
                        <td class="ticket-id">{{ $ticket->ticket_id }}</td>

                        <td class="desc-col" title="{{ $ticket->description }}">
                           <a href="{{ ($isSupport ?? false) ? route('support.ticket.show', $ticket->id) : route('admin.tickets.show', $ticket->id) }}">
                            {{ $ticket->title }}
                           </a>
                        </td>

                        <td class="cat-col">
                           {{ $ticket->ticketCategory->name ?? $ticket->category ?? '—' }}
                        </td>
                        <td>
                            <div class="requester">
                                <span>{{ ($ticket->creator)->name ?? '—' }}</span>
                            </div>
                        </td>
                        <td>{{ optional($ticket->assignedTeamMember)->name ?? '—' }}</td>                        <td>
                            <span class="badge badge-{{ str_replace(' ', '_', strtolower($ticket->status)) }}">
                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ strtolower($ticket->priority) }}">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </td>
                        <td class="date-cell">{{ $ticket->created_at->format('d M, h:i A') }}</td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="7">No tickets yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- ── QUICK STATS BAR — fills space below the table ── --}}
        @php
            $priTotal  = max($totalTickets, 1);
            $resolvedRate = $priTotal > 0 ? round(($completedTickets / $priTotal) * 100) : 0;
            $pendingCount = ($statusCounts['open'] ?? 0) + ($statusCounts['in_progress'] ?? 0) + ($statusCounts['on_hold'] ?? 0);
        @endphp
        <div class="quick-stats-bar">
            <div class="qs-item">
                <span class="qs-label">Resolution Rate</span>
                <span class="qs-value">{{ $resolvedRate }}%</span>
                <span class="qs-sub qs-up">↑ of all tickets</span>
            </div>
            <div class="qs-item">
                <span class="qs-label">Pending</span>
                <span class="qs-value">{{ $pendingCount }}</span>
                <span class="qs-sub qs-neutral">open + in progress</span>
            </div>
            <div class="qs-item">
                <span class="qs-label">Overdue</span>
                <span class="qs-value">{{ $overdueTickets }}</span>
                <span class="qs-sub qs-down">{{ $totalTickets > 0 ? round($overdueTickets/$totalTickets*100,1) : 0 }}% of total</span>
            </div>
            <div class="qs-item">
                <span class="qs-label">Within SLA</span>
                <span class="qs-value">{{ $slaPercent }}%</span>
                <span class="qs-sub qs-up">{{ $withinSla }} of {{ $totalWithDue }}</span>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN -->
    <div class="right-col">

        <!-- PRIORITY + TOP CATEGORIES -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Tickets by Priority</div>
            </div>
            <div class="priority-section">
                @php
                    $priTotal = max($totalTickets, 1);
                    $priorities = [
                        ['color' => '#dc2626', 'label' => 'Urgent',  'count' => $urgentCount],
                        ['color' => '#ea580c', 'label' => 'High',    'count' => $highCount],
                        ['color' => '#f59e0b', 'label' => 'Medium',  'count' => $mediumCount],
                        ['color' => '#16a34a', 'label' => 'Low',     'count' => $lowCount],
                    ];
                @endphp
                @foreach($priorities as $pri)
                    <div class="pri-row">
                        <div class="pri-meta">
                            <span class="pri-label" style="color:{{ $pri['color'] }}">● {{ $pri['label'] }}</span>
                            <span class="pri-count">{{ $pri['count'] }} ({{ round($pri['count']/$priTotal*100,1) }}%)</span>
                        </div>
                        <div class="bar-track">
                            <div class="bar-fill" style="width:{{ round($pri['count']/$priTotal*100) }}%; background:{{ $pri['color'] }}"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <hr class="inner-divider">
            <div class="inner-section-title">Top Categories</div>
            <div class="categories-section">
                @forelse($topCategories ?? [] as $cat)
                    <div class="cat-row">
                        <span>{{ $cat->name ?? $cat->category ?? '—' }}</span>
                        <span class="cat-count">{{ $cat->total }}</span>
                    </div>
                @empty
                    <div style="font-size:12px; color:#000000; padding:6px 0;">No categories yet</div>
                @endforelse
            </div>
        </div>

        <!-- SLA + TICKET BREAKDOWN -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">SLA Compliance</div>
            </div>
            <div class="sla-body">
                <div class="sla-ring">
                    <canvas id="slaChart" width="130" height="130"></canvas>
                    <div class="sla-center">
                        <span class="sla-pct">{{ $slaPercent }}%</span>
                        <span class="sla-lbl">Within SLA</span>
                    </div>
                </div>
                <div class="sla-stat">{{ $withinSla }} of {{ $totalWithDue }} tickets<br>within SLA target</div>
            </div>

            <hr class="inner-divider">
            <div class="inner-section-title">Ticket Breakdown</div>
            <div class="breakdown-section">
                @php
                    $breakdownItems = [
                        ['label' => 'Open',        'count' => $statusCounts['open']        ?? 0, 'color' => '#2563eb'],
                        ['label' => 'In Progress',  'count' => $statusCounts['in_progress'] ?? 0, 'color' => '#7c3aed'],
                        ['label' => 'On Hold',      'count' => $statusCounts['on_hold']     ?? 0, 'color' => '#f59e0b'],
                        ['label' => 'Completed',    'count' => $statusCounts['completed']   ?? 0, 'color' => '#16a34a'],
                        ['label' => 'Closed',       'count' => $statusCounts['closed']      ?? 0, 'color' => '#94a3b8'],
                    ];
                @endphp
                @foreach($breakdownItems as $item)
                    <div class="breakdown-row">
                        <span>{{ $item['label'] }}</span>
                        <span class="breakdown-count" style="color:{{ $item['color'] }}">{{ $item['count'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const lineLabels    = @json($lineLabels ?? []);
    const lineCreated   = @json($lineCreated ?? []);
    const lineCompleted = @json($lineCompleted ?? []);

    // LINE CHART
    const lineCtx = document.getElementById('lineChart');
    if (lineCtx) {
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: lineLabels,
                datasets: [
                    {
                        label: 'Created',
                        data: lineCreated,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37,99,235,0.08)',
                        tension: 0.4, fill: true,
                        pointBackgroundColor: '#2563eb', pointRadius: 4,
                    },
                    {
                        label: 'Completed',
                        data: lineCompleted,
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22,163,74,0.06)',
                        tension: 0.4, fill: true,
                        pointBackgroundColor: '#16a34a', pointRadius: 4,
                        borderDash: [5, 3],
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#000000' } },
                    y: { grid: { color: '#f3f4f6' }, ticks: { font: { size: 11 }, color: '#000000', stepSize: 1 } }
                }
            }
        });
    }

    // DONUT CHART
    const donutCtx = document.getElementById('donutChart');
    if (donutCtx) {
        new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Open','In Progress','On Hold','Completed','Closed'],
                datasets: [{
                    data: @json($statusData ?? []),
                    backgroundColor: ['#2563eb','#7c3aed','#f59e0b','#16a34a','#94a3b8'],
                    borderWidth: 2, borderColor: '#fff',
                }]
            },
            options: {
                cutout: '70%',
                plugins: { legend: { display: false } },
                responsive: true, maintainAspectRatio: false,
            }
        });
    }

    // SLA CHART
    const slaCtx = document.getElementById('slaChart');
    if (slaCtx) {
        const slaPercent = {{ $slaPercent ?? 0 }};
        new Chart(slaCtx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [slaPercent, 100 - slaPercent],
                    backgroundColor: ['#16a34a', '#f1f5f9'],
                    borderWidth: 0,
                }]
            },
            options: { cutout: '78%', plugins: { legend: { display: false } } }
        });
    }

});
</script>
@endpush
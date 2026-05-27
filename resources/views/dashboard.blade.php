@extends('layout')

@section('title', 'Support Dashboard')

@section('content')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

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

<!-- CHARTS -->
<div class="charts-row">

    <!-- LINE CHART -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Tickets Over Time</div>
        </div>

        <div class="card-body" style="padding-top:12px">
            <div style="position:relative; height:200px">
                <canvas id="lineChart"></canvas>
            </div>
        </div>
    </div>

    <!-- DONUT CHART -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Tickets by Status</div>
        </div>

        <div class="card-body" style="padding-top:12px">
            <div style="position:relative; height:140px; width:140px; margin:0 auto">
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
                        <span class="legend-right">
                            {{ $cnt }} ({{ round($cnt/$total*100,1) }}%)
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- BOTTOM SECTION -->
<div class="bottom-row">

    <!-- RECENT TICKETS -->
    <div class="card">
        <div class="tbl-header">
            <div class="card-title">Recent Tickets</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Description</th>
                    <th>Created By</th>
                    <th>Assigned To</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Created</th>
                </tr>
            </thead>

            <tbody>
                @forelse($recentTickets as $ticket)
                    <tr>
                        <td class="ticket-id">{{ $ticket->id }}</td>

                        <td>
                            <div class="ticket-subject" title="{{ $ticket->description }}">
                                {{ $ticket->description }}
                            </div>
                        </td>

                        <td>
                            <div class="requester">
                                <div class="avatar-sm">
                                    {{ strtoupper(substr($ticket->createdBy->name ?? '?', 0, 2)) }}
                                </div>
                                <span>{{ $ticket->createdBy->name ?? '—' }}</span>
                            </div>
                        </td>

                        <td>{{ $ticket->assignedTo->name ?? '—' }}</td>

                        <td>
                            <span class="badge badge-{{ str_replace(' ', '_', strtolower($ticket->status)) }}">
                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                            </span>
                        </td>

                        <td>
                            <span class="badge badge-{{ strtolower($ticket->priority) }}">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </td>

                        <td style="color:var(--text-muted); font-size:11.5px; white-space:nowrap">
                            {{ $ticket->created_at->format('d M, h:i A') }}
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="7">No tickets yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PRIORITY -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Tickets by Priority</div>
        </div>

        <div class="priority-section">

            @php
                $priTotal = max($totalTickets, 1);
                $priorities = [
                    'urgent' => ['color' => 'var(--red)', 'label' => 'Urgent', 'count' => $urgentCount],
                    'high'   => ['color' => 'var(--orange)', 'label' => 'High', 'count' => $highCount],
                    'medium' => ['color' => 'var(--amber)', 'label' => 'Medium', 'count' => $mediumCount],
                    'low'    => ['color' => 'var(--green)', 'label' => 'Low', 'count' => $lowCount],
                ];
            @endphp

            @foreach($priorities as $pri)
                <div class="pri-row">
                    <div class="pri-meta">
                        <span class="pri-label" style="color:{{ $pri['color'] }}">
                            ● {{ $pri['label'] }}
                        </span>

                        <span class="pri-count">
                            {{ $pri['count'] }} ({{ round($pri['count']/$priTotal*100,1) }}%)
                        </span>
                    </div>

                    <div class="bar-track">
                        <div class="bar-fill"
                             style="width:{{ round($pri['count']/$priTotal*100) }}%;
                                    background:{{ $pri['color'] }}">
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    <!-- SLA -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">SLA Compliance</div>
        </div>

        <div class="sla-body">
            <div class="sla-ring">
                <canvas id="slaChart" width="110" height="110"></canvas>
                <div class="sla-center">
                    <span class="sla-pct">{{ $slaPercent }}%</span>
                    <span class="sla-lbl">Within SLA</span>
                </div>
            </div>

            <div class="sla-stat">
                <span>{{ $withinSla }}</span> of {{ $totalWithDue }} tickets within SLA
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
                        tension: 0.4,
                        fill: false
                    },
                    {
                        label: 'Completed',
                        data: lineCompleted,
                        borderColor: '#16a34a',
                        tension: 0.4,
                        fill: false
                    }
                ]
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
                    backgroundColor: [
                        '#2563eb',
                        '#7c3aed',
                        '#f59e0b',
                        '#16a34a',
                        '#94a3b8'
                    ]
                }]
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
                    backgroundColor: ['#16a34a', '#f1f5f9']
                }]
            },
            options: {
                cutout: '75%'
            }
        });
    }

});
</script>
@endpush
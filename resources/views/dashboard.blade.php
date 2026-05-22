<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Support Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
    <style>
        :root {
            --bg-page:      #f1f3f6;
            --bg-white:     #ffffff;
            --accent:       #2563eb;
            --accent-light: #eff6ff;
            --text-primary: #1a202c;
            --text-muted:   #64748b;
            --text-light:   #94a3b8;
            --border:       #e5e7eb;
            --radius-sm:    6px;
            --radius-md:    10px;
            --radius-lg:    14px;
            --shadow-sm:    0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
            --shadow-md:    0 4px 12px rgba(0,0,0,.08);
            --green:  #16a34a; --green-bg:  #f0fdf4; --green-light: #dcfce7;
            --amber:  #d97706; --amber-bg:  #fffbeb; --amber-light: #fef3c7;
            --red:    #dc2626; --red-bg:    #fef2f2; --red-light:   #fee2e2;
            --blue:   #2563eb; --blue-bg:   #eff6ff; --blue-light:  #dbeafe;
            --purple: #7c3aed; --purple-bg: #f5f3ff;
            --orange: #ea580c; --orange-bg: #fff7ed;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg-page); color: var(--text-primary); display: flex; height: 100vh; overflow: hidden; font-size: 14px; }

        /* ══ SIDEBAR (ticket system style) ══ */
        .sidebar {
            width: 220px;
            background: #fff;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }
        .sidebar-logo {
            padding: 16px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .logo-icon {
            width: 36px; height: 36px;
            background: #dbeafe;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: #1d4ed8; font-size: 16px;
        }
        .logo-text { font-size: 13px; font-weight: 600; color: #111827; line-height: 1.3; }

        .sidebar-nav { flex: 1; padding: 8px 0; overflow-y: auto; }

        .nav-section-title {
            padding: 10px 16px;
            font-size: 11px; font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-top: 6px;
        }
        .nav-section-header {
            display: flex; align-items: center; gap: 8px;
            padding: 9px 16px;
            font-size: 13px; font-weight: 600;
            color: #374151;
        }
        .nav-section-header i { font-size: 14px; color: #6b7280; }

        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 16px 9px 32px;
            font-size: 13px; color: #6b7280;
            text-decoration: none;
            border-left: 2px solid transparent;
            transition: all 0.15s;
        }
        .nav-link:hover { background: #f3f4f6; color: #111827; }
        .nav-link.active { color: #1d4ed8; background: #dbeafe; border-left-color: #1d4ed8; font-weight: 500; }
        .nav-link i { font-size: 13px; width: 16px; text-align: center; }
        .nav-link .nav-badge {
            margin-left: auto;
            background: var(--accent);
            color: #fff;
            font-size: 10px; font-weight: 600;
            padding: 1px 6px;
            border-radius: 20px;
            min-width: 20px; text-align: center;
        }

        .sidebar-footer {
            padding: 12px 16px;
            border-top: 1px solid var(--border);
        }
        .sidebar-footer a, .sidebar-footer button {
            display: flex; align-items: center; gap: 8px;
            font-size: 13px; color: #6b7280;
            text-decoration: none;
            padding: 8px 10px; border-radius: 6px;
            width: 100%; background: none; border: none; cursor: pointer;
        }
        .sidebar-footer a:hover { background: #f3f4f6; color: #374151; }
        .sidebar-footer .logout-btn { color: #dc2626; }
        .sidebar-footer .logout-btn:hover { background: #fef2f2; }

        /* ══ MAIN ══ */
        .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }

        .topbar {
            background: var(--bg-white);
            border-bottom: 1px solid var(--border);
            padding: 0 24px;
            height: 56px;
            display: flex; align-items: center; justify-content: space-between;
            flex-shrink: 0;
        }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .topbar-left h2 { font-size: 15px; font-weight: 600; color: #111827; }
        .topbar-left p  { font-size: 12px; color: var(--text-muted); margin-top: 1px; }
        .topbar-right { display: flex; align-items: center; gap: 12px; }

        .admin-badge  { background: #fef3c7; color: #92400e; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; }
        .support-badge{ background: #dcfce7; color: #166534; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; }
        .user-badge-role { background: #dbeafe; color: #1d4ed8; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; }

        .user-badge { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #6b7280; }
        .avatar { width: 30px; height: 30px; border-radius: 50%; background: #fde68a; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; color: #92400e; }

        /* ══ CONTENT ══ */
        .content { padding: 20px 24px; flex: 1; overflow-y: auto; }

        /* METRIC CARDS */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }
        .metric-card {
            background: var(--bg-white);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            padding: 18px 20px;
            display: flex; align-items: center; gap: 14px;
            box-shadow: var(--shadow-sm);
            transition: box-shadow .2s, transform .2s;
        }
        .metric-card:hover { box-shadow: var(--shadow-md); transform: translateY(-1px); }
        .metric-icon {
            width: 44px; height: 44px;
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0;
        }
        .metric-icon.blue   { background: var(--blue-bg);   color: var(--blue); }
        .metric-icon.green  { background: var(--green-bg);  color: var(--green); }
        .metric-icon.amber  { background: var(--amber-bg);  color: var(--amber); }
        .metric-icon.red    { background: var(--red-bg);    color: var(--red); }
        .metric-body { flex: 1; min-width: 0; }
        .metric-value { font-size: 26px; font-weight: 600; color: var(--text-primary); line-height: 1; margin-bottom: 4px; font-family: 'DM Mono', monospace; }
        .metric-label { font-size: 12px; color: var(--text-muted); }

        /* CHARTS ROW */
        .charts-row {
            display: grid;
            grid-template-columns: 1fr 280px;
            gap: 16px;
            margin-bottom: 20px;
        }
        .card {
            background: var(--bg-white);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }
        .card-header {
            padding: 16px 20px 0;
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-title { font-size: 14px; font-weight: 600; color: var(--text-primary); }
        .card-body  { padding: 16px 20px 20px; }

        .status-legend { display: flex; flex-direction: column; gap: 8px; margin-top: 12px; }
        .legend-item { display: flex; align-items: center; justify-content: space-between; font-size: 12.5px; }
        .legend-left { display: flex; align-items: center; gap: 8px; }
        .legend-dot  { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
        .legend-name { color: var(--text-primary); }
        .legend-right { color: var(--text-muted); font-family: 'DM Mono', monospace; font-size: 12px; }

        /* BOTTOM ROW */
        .bottom-row {
            display: grid;
            grid-template-columns: 1fr 220px 220px;
            gap: 16px;
        }

        /* TABLE */
        .tbl-header {
            padding: 14px 20px;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid var(--border);
        }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            text-align: left; font-size: 11px; font-weight: 600;
            color: var(--text-muted); text-transform: uppercase;
            letter-spacing: .5px; padding: 10px 14px;
            border-bottom: 1px solid var(--border); background: #fafbfc;
        }
        tbody td {
            padding: 11px 14px; font-size: 12.5px;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #fafbfc; }

        .ticket-id { font-family: 'DM Mono', monospace; font-size: 12px; color: var(--accent); font-weight: 500; }
        .ticket-subject { font-weight: 500; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .requester { display: flex; align-items: center; gap: 7px; }
        .avatar-sm {
            width: 24px; height: 24px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 9px; font-weight: 600; flex-shrink: 0;
            background: var(--blue-bg); color: var(--blue);
        }

        /* BADGES */
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 9px; border-radius: 20px; font-size: 11px; font-weight: 500; }
        .badge-open        { background: var(--blue-bg);   color: var(--blue); }
        .badge-in_progress { background: var(--amber-bg);  color: var(--amber); }
        .badge-on_hold     { background: #fff7ed;           color: #c2410c; }
        .badge-completed   { background: var(--green-bg);  color: var(--green); }
        .badge-closed      { background: #f1f5f9;           color: #475569; }
        .badge-urgent      { background: var(--red-bg);    color: var(--red); }
        .badge-high        { background: var(--orange-bg); color: var(--orange); }
        .badge-medium      { background: var(--amber-bg);  color: var(--amber); }
        .badge-low         { background: var(--green-bg);  color: var(--green); }

        /* PRIORITY BARS */
        .priority-section { padding: 0 20px 20px; }
        .pri-row { margin-bottom: 12px; }
        .pri-meta { display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 5px; }
        .pri-label { font-weight: 500; color: var(--text-primary); }
        .pri-count { color: var(--text-muted); font-family: 'DM Mono', monospace; }
        .bar-track { height: 6px; background: var(--bg-page); border-radius: 99px; overflow: hidden; }
        .bar-fill  { height: 100%; border-radius: 99px; }

        /* SLA */
        .sla-body { padding: 16px 20px 20px; display: flex; flex-direction: column; align-items: center; }
        .sla-ring { position: relative; width: 110px; height: 110px; margin-bottom: 12px; }
        .sla-center { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; }
        .sla-pct  { font-size: 22px; font-weight: 600; color: var(--text-primary); font-family: 'DM Mono', monospace; }
        .sla-lbl  { font-size: 10px; color: var(--text-muted); }
        .sla-stat { font-size: 12px; color: var(--text-muted); text-align: center; }
        .sla-stat span { font-weight: 600; color: var(--text-primary); }
        .sla-badge { margin-top: 8px; font-size: 11px; color: var(--green); background: var(--green-bg); padding: 3px 10px; border-radius: 20px; }

        .tbl-footer { padding: 10px 16px; border-top: 1px solid var(--border); font-size: 12px; color: var(--text-muted); }
        .empty-row td { text-align: center; color: var(--text-muted); padding: 28px; font-size: 13px; }

        /* VIEW ALL LINK */
        .btn-ghost {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 6px 12px; border-radius: var(--radius-sm);
            font-size: 12px; font-weight: 500; font-family: 'DM Sans', sans-serif;
            cursor: pointer; border: 1px solid var(--border);
            background: transparent; color: var(--text-muted);
            text-decoration: none; transition: all .15s;
        }
        .btn-ghost:hover { background: var(--bg-page); }
    </style>
</head>
<body>

{{-- ══ SIDEBAR (ticket system style) ══ --}}
<div class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon"><i class="fas fa-headset"></i></div>
        <div class="logo-text">Ticket<br>System</div>
    </div>

    <nav class="sidebar-nav">

        <div class="nav-section-title">
            {{ Auth::user()->role === 'admin' ? 'Admin' : (Auth::user()->role === 'support' ? 'Support' : 'Menu') }}
        </div>

        {{-- Dashboard link — active on this page --}}
        <div class="nav-section-header">
            <i class="fas fa-th-large"></i> Main
        </div>
        <a href="{{ route('dashboard') }}"
           class="nav-link active">
            <i class="fas fa-chart-pie"></i> Dashboard
        </a>

        {{-- Tickets --}}
        <div class="nav-section-header" style="margin-top:4px">
            <i class="fas fa-ticket-alt"></i> Tickets
        </div>
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.tickets.index') }}" class="nav-link">
                <i class="fas fa-list-ul"></i> All Tickets
                @if($inProgressCount > 0)
                    <span class="nav-badge">{{ $inProgressCount }}</span>
                @endif
            </a>
            <a href="{{ route('ticketsystem.assigned') }}" class="nav-link">
                <i class="fas fa-user-check"></i> Assigned To Me
            </a>
        @endif
        @if(Auth::user()->role === 'support')
            <a href="{{ route('support.tickets') }}" class="nav-link">
                <i class="fas fa-user-check"></i> Assigned To Me
            </a>
        @endif
        <a href="{{ route('ticketsystem.my') }}" class="nav-link">
            <i class="fas fa-ticket-alt"></i> My Tickets
        </a>

        {{-- Admin-only sections --}}
        @if(Auth::user()->role === 'admin')
            <div class="nav-section-header" style="margin-top:4px">
                <i class="fas fa-tags"></i> Categories
            </div>
            <a href="{{ route('admin.ticket-categories.index') }}" class="nav-link">
                <i class="fas fa-th-list"></i> All Categories
            </a>

            <div class="nav-section-header" style="margin-top:4px">
                <i class="fas fa-users"></i> Support Team
            </div>
            <a href="{{ route('admin.support-team.index') }}" class="nav-link">
                <i class="fas fa-users"></i> All Members
            </a>

            <div class="nav-section-header" style="margin-top:4px">
                <i class="fas fa-sliders-h"></i> Settings
            </div>
            <a href="{{ route('admin.ticket-options.index') }}" class="nav-link">
                <i class="fas fa-sliders-h"></i> Ticket Options
            </a>
            <a href="{{ route('admin.tickets.duedates') }}" class="nav-link">
                <i class="fas fa-calendar-alt"></i> Edit Due Dates
            </a>
        @endif

    </nav>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST" style="margin:0">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>
</div>

{{-- ══ MAIN ══ --}}
<div class="main">

    {{-- TOPBAR --}}
    <div class="topbar">
        <div class="topbar-left">
            <div>
                <h2>Ticket Dashboard</h2>
                <p>Overview of all support tickets and their status</p>
            </div>
        </div>
        <div class="topbar-right">
            @if(Auth::user()->role === 'admin')
                <span class="admin-badge"><i class="fas fa-shield-alt"></i> Admin</span>
            @elseif(Auth::user()->role === 'support')
                <span class="support-badge"><i class="fas fa-headset"></i> Support</span>
            @else
                <span class="user-badge-role"><i class="fas fa-user"></i> User</span>
            @endif
            <div class="user-badge">
                <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                <span>{{ Auth::user()->name }}</span>
            </div>
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="content">

        {{-- METRIC CARDS --}}
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

        {{-- CHARTS ROW --}}
        <div class="charts-row">
            {{-- Line Chart --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Tickets Over Time</div>
                    <span style="font-size:11px;color:var(--text-muted);background:var(--bg-page);border:1px solid var(--border);padding:4px 10px;border-radius:20px;">
                        <i class="fas fa-calendar-alt" style="font-size:10px"></i> Last 7 Days
                    </span>
                </div>
                <div class="card-body" style="padding-top:12px">
                    <div style="position:relative; height:200px">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Donut Chart --}}
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
                                <span class="legend-right">{{ $cnt }} ({{ round($cnt/$total*100,1) }}%)</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- BOTTOM ROW --}}
        <div class="bottom-row">

            {{-- RECENT TICKETS TABLE --}}
            <div class="card" style="overflow:hidden">
                <div class="tbl-header">
                    <div class="card-title">Recent Tickets</div>
                    <a href="{{ route('admin.tickets.index') }}" class="btn-ghost">View All</a>
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
                            <td><span class="ticket-id">{{ $ticket->id }}</span></td>
                            <td>
                                <div class="ticket-subject" title="{{ $ticket->description }}">
                                    {{ $ticket->description }}
                                </div>
                            </td>
                            <td>
                                <div class="requester">
                                    <div class="avatar-sm">{{ strtoupper(substr($ticket->createdBy->name ?? '?', 0, 2)) }}</div>
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
                            <td colspan="7"><i class="fas fa-inbox" style="margin-right:6px"></i>No tickets yet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="tbl-footer">
                    Showing {{ $recentTickets->count() }} of {{ $totalTickets }} tickets &nbsp;·&nbsp;
                    <a href="{{ route('admin.tickets.index') }}" style="color:var(--accent);text-decoration:none">View all →</a>
                </div>
            </div>

            {{-- TICKETS BY PRIORITY --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Tickets by Priority</div>
                </div>
                <div class="priority-section" style="margin-top:14px">
                    @php
                        $priTotal = max($totalTickets, 1);
                        $priorities = [
                            'urgent' => ['color' => 'var(--red)',    'label' => 'Urgent', 'count' => $urgentCount],
                            'high'   => ['color' => 'var(--orange)', 'label' => 'High',   'count' => $highCount],
                            'medium' => ['color' => 'var(--amber)',  'label' => 'Medium', 'count' => $mediumCount],
                            'low'    => ['color' => 'var(--green)',  'label' => 'Low',    'count' => $lowCount],
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

                    <div style="margin-top:20px; border-top:1px solid var(--border); padding-top:14px">
                        <div style="font-size:12.5px; font-weight:600; color:var(--text-primary); margin-bottom:10px">Top Categories</div>
                        <div style="display:flex; flex-direction:column; gap:7px">
                            @forelse($topCategories as $cat)
                            <div style="display:flex; justify-content:space-between; font-size:12px">
                                <span style="color:var(--text-muted)">{{ $cat->category ?: 'Uncategorised' }}</span>
                                <span style="font-weight:500; font-family:'DM Mono',monospace">{{ $cat->count }}</span>
                            </div>
                            @empty
                            <div style="font-size:12px;color:var(--text-muted)">No categories yet</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- SLA COMPLIANCE --}}
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
                        <span>{{ $withinSla }}</span> of {{ $totalWithDue }} tickets<br>within SLA target
                    </div>
                    @if($totalWithDue === 0)
                        <div class="sla-badge" style="background:var(--blue-bg);color:var(--blue)">No due dates set</div>
                    @elseif($slaPercent >= 90)
                        <div class="sla-badge"><i class="fas fa-check" style="font-size:9px;margin-right:3px"></i> On track</div>
                    @else
                        <div class="sla-badge" style="background:var(--red-bg);color:var(--red)">
                            <i class="fas fa-exclamation" style="font-size:9px;margin-right:3px"></i> Needs attention
                        </div>
                    @endif

                    <div style="margin-top:18px; width:100%; border-top:1px solid var(--border); padding-top:14px">
                        <div style="font-size:12.5px; font-weight:600; color:var(--text-primary); margin-bottom:10px">Ticket Breakdown</div>
                        <div style="display:flex; flex-direction:column; gap:6px">
                            <div style="display:flex; justify-content:space-between; font-size:12px">
                                <span style="color:var(--text-muted)">Open</span>
                                <span style="font-weight:500">{{ $openTickets }}</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; font-size:12px">
                                <span style="color:var(--text-muted)">In Progress</span>
                                <span style="font-weight:500">{{ $statusCounts['in_progress'] ?? 0 }}</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; font-size:12px">
                                <span style="color:var(--text-muted)">Overdue</span>
                                <span style="font-weight:500; color:{{ $overdueTickets > 0 ? 'var(--red)' : 'var(--green)' }}">{{ $overdueTickets }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- /bottom-row --}}
    </div>{{-- /content --}}
</div>{{-- /main --}}

<script>
// ── LINE CHART ──────────────────────────────────────────────
const lineLabels    = @json($lineLabels);
const lineCreated   = @json($lineCreated);
const lineCompleted = @json($lineCompleted);

const lineCtx = document.getElementById('lineChart').getContext('2d');
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
                fill: true, tension: 0.45, borderWidth: 2,
                pointBackgroundColor: '#2563eb', pointRadius: 3, pointHoverRadius: 5,
            },
            {
                label: 'Completed',
                data: lineCompleted,
                borderColor: '#16a34a',
                backgroundColor: 'rgba(22,163,74,0.06)',
                fill: true, tension: 0.45, borderWidth: 2,
                pointBackgroundColor: '#16a34a', pointRadius: 3, pointHoverRadius: 5,
                borderDash: [5, 3],
            }
        ]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: { backgroundColor: '#1a202c', titleColor: '#fff', bodyColor: 'rgba(255,255,255,.75)', padding: 10, cornerRadius: 6 }
        },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#94a3b8' } },
            y: { grid: { color: 'rgba(0,0,0,.04)' }, ticks: { font: { size: 11 }, color: '#94a3b8', stepSize: 1, precision: 0 }, beginAtZero: true }
        }
    }
});

// Custom legend
const lineChartParent = document.getElementById('lineChart').closest('.card-body');
const legendDiv = document.createElement('div');
legendDiv.style.cssText = 'display:flex;gap:16px;margin-bottom:8px;font-size:12px;color:#64748b;';
legendDiv.innerHTML = `
  <span style="display:flex;align-items:center;gap:5px"><span style="width:18px;height:2px;background:#2563eb;display:inline-block;border-radius:2px"></span>Created</span>
  <span style="display:flex;align-items:center;gap:5px"><span style="width:18px;height:2px;background:#16a34a;display:inline-block;border-radius:2px"></span>Completed</span>
`;
lineChartParent.insertBefore(legendDiv, lineChartParent.firstChild);

// ── DONUT CHART ──────────────────────────────────────────────
const statusData = @json($statusData);
const donutCtx = document.getElementById('donutChart').getContext('2d');
new Chart(donutCtx, {
    type: 'doughnut',
    data: {
        labels: ['Open', 'In Progress', 'On Hold', 'Completed', 'Closed'],
        datasets: [{
            data: statusData,
            backgroundColor: ['#2563eb', '#7c3aed', '#f59e0b', '#16a34a', '#94a3b8'],
            borderWidth: 2, borderColor: '#ffffff', hoverOffset: 4
        }]
    },
    options: {
        cutout: '72%', responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: { backgroundColor: '#1a202c', titleColor: '#fff', bodyColor: 'rgba(255,255,255,.75)', padding: 10, cornerRadius: 6 }
        }
    }
});

// ── SLA CHART ──────────────────────────────────────────────
const slaPercent = {{ $slaPercent }};
const slaCtx = document.getElementById('slaChart').getContext('2d');
new Chart(slaCtx, {
    type: 'doughnut',
    data: {
        datasets: [{
            data: [slaPercent, 100 - slaPercent],
            backgroundColor: [slaPercent >= 90 ? '#16a34a' : '#dc2626', '#f0fdf4'],
            borderWidth: 0, hoverOffset: 0
        }]
    },
    options: {
        cutout: '82%', responsive: false,
        plugins: { legend: { display: false }, tooltip: { enabled: false } }
    }
});
</script>

</body>
</html>
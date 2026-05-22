<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
    <style>
        :root {
            --bg-page:      #f4f6f9;
            --bg-white:     #ffffff;
            --bg-sidebar:   #0f1c2e;
            --sidebar-w:    220px;
            --accent:       #2563eb;
            --accent-light: #eff6ff;
            --text-primary: #1a202c;
            --text-muted:   #64748b;
            --text-light:   #94a3b8;
            --border:       #e8edf2;
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
        body { font-family: 'DM Sans', sans-serif; background: var(--bg-page); color: var(--text-primary); display: flex; min-height: 100vh; font-size: 14px; }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--bg-sidebar);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
        }
        .sidebar-brand {
            padding: 22px 20px 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .brand-icon {
            width: 32px; height: 32px;
            background: var(--accent);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 15px;
        }
        .brand-name { color: #fff; font-size: 15px; font-weight: 600; letter-spacing: -.2px; }

        .nav-section { padding: 14px 12px; }
        .nav-section-label {
            font-size: 10px;
            font-weight: 600;
            color: rgba(255,255,255,.3);
            text-transform: uppercase;
            letter-spacing: .8px;
            padding: 0 8px;
            margin-bottom: 6px;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 10px;
            border-radius: var(--radius-sm);
            color: rgba(255,255,255,.55);
            text-decoration: none;
            font-size: 13px;
            font-weight: 400;
            transition: all .15s;
            margin-bottom: 1px;
            cursor: pointer;
        }
        .nav-item i { width: 16px; text-align: center; font-size: 14px; flex-shrink: 0; }
        .nav-item:hover { background: rgba(255,255,255,.07); color: rgba(255,255,255,.9); }
        .nav-item.active { background: rgba(37,99,235,.35); color: #fff; font-weight: 500; }
        .nav-item .nav-badge {
            margin-left: auto;
            background: var(--accent);
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            padding: 1px 6px;
            border-radius: 20px;
            min-width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 14px 12px;
            border-top: 1px solid rgba(255,255,255,.07);
        }
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: var(--radius-sm);
            cursor: pointer;
        }
        .sidebar-user:hover { background: rgba(255,255,255,.07); }
        .user-avatar {
            width: 32px; height: 32px;
            background: var(--accent);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 12px; font-weight: 600;
            flex-shrink: 0;
        }
        .user-info .user-name { color: #fff; font-size: 12.5px; font-weight: 500; }
        .user-info .user-role { color: rgba(255,255,255,.4); font-size: 11px; }

        /* ── MAIN ── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            background: var(--bg-white);
            border-bottom: 1px solid var(--border);
            padding: 0 28px;
            height: 58px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .topbar-left h2 { font-size: 16px; font-weight: 600; color: var(--text-primary); }
        .topbar-left p  { font-size: 12px; color: var(--text-muted); margin-top: 1px; }
        .topbar-right { display: flex; align-items: center; gap: 10px; }

        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 8px 16px; border-radius: var(--radius-sm);
            font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 500;
            cursor: pointer; border: none; transition: all .15s; text-decoration: none;
        }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-ghost { background: transparent; color: var(--text-muted); border: 1px solid var(--border); }
        .btn-ghost:hover { background: var(--bg-page); }

        .icon-btn {
            width: 36px; height: 36px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            background: transparent;
            display: flex; align-items: center; justify-content: center;
            color: var(--text-muted);
            cursor: pointer;
            position: relative;
        }
        .icon-btn:hover { background: var(--bg-page); }
        .notif-dot {
            position: absolute; top: 7px; right: 7px;
            width: 7px; height: 7px;
            background: var(--red);
            border-radius: 50%;
            border: 1.5px solid var(--bg-white);
        }

        .content { padding: 24px 28px; flex: 1; }

        /* ── METRIC CARDS ── */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 22px;
        }
        .metric-card {
            background: var(--bg-white);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: var(--shadow-sm);
            transition: box-shadow .2s, transform .2s;
        }
        .metric-card:hover { box-shadow: var(--shadow-md); transform: translateY(-1px); }
        .metric-icon {
            width: 44px; height: 44px;
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .metric-icon.blue   { background: var(--blue-bg);   color: var(--blue); }
        .metric-icon.green  { background: var(--green-bg);  color: var(--green); }
        .metric-icon.amber  { background: var(--amber-bg);  color: var(--amber); }
        .metric-icon.red    { background: var(--red-bg);    color: var(--red); }
        .metric-icon.orange { background: var(--orange-bg); color: var(--orange); }
        .metric-body { flex: 1; min-width: 0; }
        .metric-value { font-size: 26px; font-weight: 600; color: var(--text-primary); line-height: 1; margin-bottom: 4px; font-family: 'DM Mono', monospace; }
        .metric-label { font-size: 12px; color: var(--text-muted); }

        /* ── CHARTS ROW ── */
        .charts-row {
            display: grid;
            grid-template-columns: 1fr 280px;
            gap: 16px;
            margin-bottom: 22px;
        }
        .card {
            background: var(--bg-white);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }
        .card-header {
            padding: 16px 20px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-title { font-size: 14px; font-weight: 600; color: var(--text-primary); }
        .card-body  { padding: 16px 20px 20px; }

        /* STATUS LEGEND */
        .status-legend { display: flex; flex-direction: column; gap: 8px; margin-top: 12px; }
        .legend-item { display: flex; align-items: center; justify-content: space-between; font-size: 12.5px; }
        .legend-left { display: flex; align-items: center; gap: 8px; }
        .legend-dot  { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
        .legend-name { color: var(--text-primary); }
        .legend-right { color: var(--text-muted); font-family: 'DM Mono', monospace; font-size: 12px; }

        /* ── BOTTOM ROW ── */
        .bottom-row {
            display: grid;
            grid-template-columns: 1fr 220px 220px;
            gap: 16px;
        }

        /* RECENT TICKETS TABLE */
        .tbl-header {
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
        }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 10px 14px;
            border-bottom: 1px solid var(--border);
            background: #fafbfc;
        }
        tbody td {
            padding: 11px 14px;
            font-size: 12.5px;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #fafbfc; }

        .ticket-id {
            font-family: 'DM Mono', monospace;
            font-size: 12px;
            color: var(--accent);
            font-weight: 500;
        }
        .ticket-subject { font-weight: 500; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .requester { display: flex; align-items: center; gap: 7px; }
        .avatar-sm {
            width: 24px; height: 24px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 9px; font-weight: 600;
            flex-shrink: 0;
            background: var(--blue-bg); color: var(--blue);
        }

        /* BADGES */
        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 9px; border-radius: 20px;
            font-size: 11px; font-weight: 500;
        }
        .badge-open       { background: var(--blue-bg);   color: var(--blue); }
        .badge-in_progress{ background: var(--amber-bg);  color: var(--amber); }
        .badge-on_hold    { background: #fff7ed;           color: #c2410c; }
        .badge-completed  { background: var(--green-bg);  color: var(--green); }
        .badge-closed     { background: #f1f5f9;           color: #475569; }
        .badge-urgent     { background: var(--red-bg);    color: var(--red); }
        .badge-high       { background: var(--orange-bg); color: var(--orange); }
        .badge-medium     { background: var(--amber-bg);  color: var(--amber); }
        .badge-low        { background: var(--green-bg);  color: var(--green); }

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
        .sla-center {
            position: absolute; inset: 0;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
        }
        .sla-pct  { font-size: 22px; font-weight: 600; color: var(--text-primary); font-family: 'DM Mono', monospace; }
        .sla-lbl  { font-size: 10px; color: var(--text-muted); }
        .sla-stat { font-size: 12px; color: var(--text-muted); text-align: center; }
        .sla-stat span { font-weight: 600; color: var(--text-primary); }
        .sla-badge { margin-top: 8px; font-size: 11px; color: var(--green); background: var(--green-bg); padding: 3px 10px; border-radius: 20px; }

        /* TFOOT */
        .tbl-footer {
            padding: 10px 16px;
            border-top: 1px solid var(--border);
            font-size: 12px;
            color: var(--text-muted);
        }

        /* ROLE BADGE */
        .role-pill {
            font-size: 11px; font-weight: 600;
            padding: 3px 10px; border-radius: 20px;
            text-transform: uppercase; letter-spacing: .4px;
        }
        .role-pill.admin { background: var(--amber-bg); color: var(--amber); }
        .role-pill.user  { background: var(--blue-bg);  color: var(--blue); }

        /* EMPTY STATE */
        .empty-row td { text-align: center; color: var(--text-muted); padding: 28px; font-size: 13px; }
    </style>
</head>
<body>

<!-- ══ SIDEBAR ══════════════════════════════════════ -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="fas fa-headset"></i></div>
        <span class="brand-name">SEEL SUPPORT</span>
    </div>

    <nav class="nav-section">
        <div class="nav-section-label">Main</div>
        <a class="nav-item active" href="#">
            <i class="fas fa-th-large"></i> Dashboard
        </a>
        <a class="nav-item" href="{{ route('admin.tickets.index') }}">
            <i class="fas fa-ticket-alt"></i> Tickets
            @if($inProgressCount > 0)
                <span class="nav-badge">{{ $inProgressCount }}</span>
            @endif
        </a>
        
    </nav>

    <nav class="nav-section" style="padding-top:4px">
        <div class="nav-section-label">Manage</div>
        @if(Auth::user()->role === 'admin')
        <a class="nav-item" href="{{ route('admin.support-team.index') }}">
            <i class="fas fa-users"></i> Support Team
        </a>
        <a class="nav-item" href="{{ route('admin.ticket-categories.index') }}">
            <i class="fas fa-tags"></i> Categories
        </a>
        @endif
        
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">{{ ucfirst(Auth::user()->role) }}</div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST" style="margin-top:6px">
            @csrf
            <button class="btn btn-ghost" style="width:100%; justify-content:center; font-size:12px; padding:7px">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>
</aside>

<!-- ══ MAIN ══════════════════════════════════════════ -->
<div class="main">

    <!-- TOPBAR -->
    <header class="topbar">
        <div class="topbar-left">
            <h2>Ticket Dashboard</h2>
            <p>Overview of all support tickets and their status</p>
        </div>
        <div class="topbar-right">
    @if(Auth::user()->role === 'admin')
        <span class="role-pill admin"><i class="fas fa-shield-alt" style="margin-right:4px"></i>Admin</span>
    @else
        <span class="role-pill user"><i class="fas fa-user" style="margin-right:4px"></i>User</span>
    @endif

    <div style="display:flex; align-items:center; gap:8px; margin-left:6px;">
        <div style="width:30px; height:30px; border-radius:50%; background:#dbeafe; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; color:#1d4ed8;">
            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
        </div>
        <span style="font-size:13px; color:#374151; font-weight:500;">{{ Auth::user()->name }}</span>
    </div>
</div>
    </header>

    <!-- CONTENT -->
    <main class="content">

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
            <!-- Line Chart -->
            <div class="card">
                <div class="card-header">
                    <div>
                        <div class="card-title">Tickets Over Time</div>
                    </div>
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

            <!-- Donut Chart -->
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

        <!-- BOTTOM ROW -->
        <div class="bottom-row">

            <!-- RECENT TICKETS TABLE -->
            <div class="card" style="overflow:hidden">
                <div class="tbl-header">
                    <div class="card-title">Recent Tickets</div>
                    <a href="{{ route('admin.tickets.index') }}" class="btn btn-ghost" style="font-size:12px; padding:6px 12px">View All</a>
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

            <!-- TICKETS BY PRIORITY -->
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

            <!-- SLA COMPLIANCE -->
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

        </div><!-- /bottom-row -->
    </main>
</div>

<script>
// ── LINE CHART ──────────────────────────────────────────────
const lineLabels   = @json($lineLabels);
const lineCreated  = @json($lineCreated);
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
                fill: true,
                tension: 0.45,
                borderWidth: 2,
                pointBackgroundColor: '#2563eb',
                pointRadius: 3,
                pointHoverRadius: 5,
            },
            {
                label: 'Completed',
                data: lineCompleted,
                borderColor: '#16a34a',
                backgroundColor: 'rgba(22,163,74,0.06)',
                fill: true,
                tension: 0.45,
                borderWidth: 2,
                pointBackgroundColor: '#16a34a',
                pointRadius: 3,
                pointHoverRadius: 5,
                borderDash: [5, 3],
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#1a202c',
                titleColor: '#fff',
                bodyColor: 'rgba(255,255,255,.75)',
                padding: 10,
                cornerRadius: 6,
            }
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: { font: { size: 11 }, color: '#94a3b8' }
            },
            y: {
                grid: { color: 'rgba(0,0,0,.04)' },
                ticks: { font: { size: 11 }, color: '#94a3b8', stepSize: 1, precision: 0 },
                beginAtZero: true
            }
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
            borderWidth: 2,
            borderColor: '#ffffff',
            hoverOffset: 4
        }]
    },
    options: {
        cutout: '72%',
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#1a202c',
                titleColor: '#fff',
                bodyColor: 'rgba(255,255,255,.75)',
                padding: 10,
                cornerRadius: 6,
            }
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
            borderWidth: 0,
            hoverOffset: 0
        }]
    },
    options: {
        cutout: '82%',
        responsive: false,
        plugins: { legend: { display: false }, tooltip: { enabled: false } }
    }
});
</script>

</body>
</html>
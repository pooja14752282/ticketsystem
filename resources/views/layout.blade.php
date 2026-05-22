<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — Ticket System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f1f3f6; display: flex; height: 100vh; overflow: hidden; }

        .sidebar { width: 220px; background: #fff; border-right: 1px solid #e5e7eb; display: flex; flex-direction: column; flex-shrink: 0; }
        .sidebar-logo { padding: 16px; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; gap: 10px; }
        .sidebar-logo .logo-icon { width: 36px; height: 36px; background: #dbeafe; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #1d4ed8; font-size: 16px; }
        .sidebar-logo .logo-text { font-size: 13px; font-weight: 600; color: #111827; line-height: 1.3; }

        .sidebar-nav { flex: 1; padding: 8px 0; overflow-y: auto; }
        .nav-section-title { padding: 10px 16px; font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.8px; margin-top: 6px; }
        .nav-link { display: flex; align-items: center; gap: 10px; padding: 9px 16px 9px 32px; font-size: 13px; color: #6b7280; text-decoration: none; border-left: 2px solid transparent; transition: all 0.15s; }
        .nav-link:hover { background: #f3f4f6; color: #111827; }
        .nav-link.active { color: #1d4ed8; background: #dbeafe; border-left-color: #1d4ed8; font-weight: 500; }
        .nav-link i { font-size: 13px; width: 16px; text-align: center; }
        .nav-section-header { display: flex; align-items: center; gap: 8px; padding: 9px 16px; font-size: 13px; font-weight: 600; color: #374151; }
        .nav-section-header i { font-size: 14px; color: #6b7280; }

        .sidebar-footer { padding: 12px 16px; border-top: 1px solid #e5e7eb; }
        .sidebar-footer a { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #6b7280; text-decoration: none; padding: 8px 10px; border-radius: 6px; }
        .sidebar-footer a:hover { background: #f3f4f6; color: #374151; }

        .due-dates-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 16px 9px 32px; font-size: 13px; color: #6b7280;
            text-decoration: none; border-left: 2px solid transparent; transition: all 0.15s;
        }
        .due-dates-link:hover { background: #f3f4f6; color: #111827; }
        .due-dates-link.active { color: #1d4ed8; background: #dbeafe; border-left-color: #1d4ed8; font-weight: 500; }
        .due-dates-link i { font-size: 13px; width: 16px; text-align: center; }

        .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; padding: 0 20px; height: 56px; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .topbar-left span { font-size: 13px; color: #6b7280; }
        .admin-badge { background: #fef3c7; color: #92400e; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; }
        .support-badge { background: #dcfce7; color: #166534; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; }
        .user-badge-role { background: #dbeafe; color: #1d4ed8; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; }
        .topbar-right { display: flex; align-items: center; gap: 12px; }
        .notif-btn { display: flex; align-items: center; gap: 6px; font-size: 13px; color: #6b7280; cursor: pointer; padding: 6px 10px; border-radius: 6px; border: none; background: none; }
        .notif-btn:hover { background: #f3f4f6; }
        .user-badge { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #6b7280; }
        .avatar { width: 30px; height: 30px; border-radius: 50%; background: #fde68a; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; color: #92400e; }

        .content { flex: 1; overflow-y: auto; padding: 20px; }
        .alert-success { background: #dcfce7; color: #166534; padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; border: 1px solid #bbf7d0; display: flex; align-items: center; gap: 8px; }
        .alert-error   { background: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; border: 1px solid #fecaca; display: flex; align-items: center; gap: 8px; }

        /* Notification Bell */
        #notif-wrapper { position: relative; }
        #notif-bell-btn { position: relative; }
        #notif-badge {
            display: none;
            position: absolute;
            top: 2px; right: 2px;
            background: #ef4444; color: #fff;
            font-size: 10px; font-weight: 700;
            border-radius: 50%;
            width: 17px; height: 17px;
            align-items: center; justify-content: center;
            pointer-events: none;
        }
        #notif-dropdown {
            display: none;
            position: absolute;
            right: 0; top: 44px;
            width: 320px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            z-index: 999;
        }
        .notif-header {
            padding: 12px 16px;
            border-bottom: 1px solid #f3f4f6;
            display: flex; align-items: center; justify-content: space-between;
        }
        .notif-header span { font-size: 13px; font-weight: 600; color: #111827; }
        .notif-mark-all { font-size: 11px; color: #1d4ed8; background: none; border: none; cursor: pointer; }
        .notif-mark-all:hover { text-decoration: underline; }
        #notif-list { max-height: 320px; overflow-y: auto; }
        .notif-item {
            padding: 12px 16px;
            border-bottom: 1px solid #f9fafb;
            cursor: pointer;
            transition: background 0.1s;
        }
        .notif-item:last-child { border-bottom: none; }
        .notif-item:hover { background: #f3f4f6; }
        .notif-item.unread { background: #eff6ff; }
        .notif-item.unread:hover { background: #dbeafe; }
        .notif-item-title { font-size: 13px; font-weight: 600; color: #111827; }
        .notif-item-msg { font-size: 12px; color: #6b7280; margin-top: 2px; }
        .notif-item-time { font-size: 11px; color: #9ca3af; margin-top: 4px; }
        .notif-empty { padding: 24px; text-align: center; color: #9ca3af; font-size: 13px; }
    </style>
    @yield('styles')
</head>
<body>

<div class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon"><i class="fas fa-ticket-alt"></i></div>
        <div class="logo-text">Ticket<br>System</div>
    </div>

    <nav class="sidebar-nav">

        <div class="nav-section-title">
            {{ Auth::user()->role === 'admin' ? 'Admin' : (Auth::user()->role === 'support' ? 'Support' : 'Menu') }}
        </div>

        <div class="nav-section-header">
            <i class="fas fa-ticket-alt"></i> Tickets
        </div>

        {{-- Admin links --}}
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.tickets.index') }}"
               class="nav-link {{ request()->routeIs('admin.tickets.index') ? 'active' : '' }}">
                <i class="fas fa-list-ul"></i> All Tickets
            </a>
            <a href="{{ route('ticketsystem.assigned') }}"
               class="nav-link {{ request()->routeIs('ticketsystem.assigned') ? 'active' : '' }}">
                <i class="fas fa-user-check"></i> Assigned to Me
            </a>
        @endif

        {{-- Support links --}}
        @if(Auth::user()->role === 'support')
            <a href="{{ route('support.tickets') }}"
               class="nav-link {{ request()->routeIs('support.tickets') ? 'active' : '' }}">
                <i class="fas fa-user-check"></i> Assigned to Me
            </a>
        @endif

        {{-- All roles --}}
        <a href="{{ route('ticketsystem.my') }}"
           class="nav-link {{ request()->routeIs('ticketsystem.my') ? 'active' : '' }}">
            <i class="fas fa-ticket-alt"></i> My Ticket
        </a>

        {{-- Admin only sections --}}
        @if(Auth::user()->role === 'admin')
            <div class="nav-section-header" style="margin-top:4px;">
                <i class="fas fa-tags"></i> Categories
            </div>
            <a href="{{ route('admin.ticket-categories.index') }}"
               class="nav-link {{ request()->routeIs('admin.ticket-categories.index') ? 'active' : '' }}">
                <i class="fas fa-th-list"></i> All Categories
            </a>

            <div class="nav-section-header" style="margin-top:4px;">
                <i class="fas fa-users"></i> Support Team
            </div>
            <a href="{{ route('admin.support-team.index') }}"
               class="nav-link {{ request()->routeIs('admin.support-team.index') ? 'active' : '' }}">
                <i class="fas fa-users"></i> All Members
            </a>

            {{-- Ticket Options --}}
            <div class="nav-section-header" style="margin-top:4px;">
                <i class="fas fa-sliders-h"></i> Settings
            </div>
            <a href="{{ route('admin.ticket-options.index') }}"
               class="nav-link {{ request()->routeIs('admin.ticket-options.index') ? 'active' : '' }}">
                <i class="fas fa-sliders-h"></i> Ticket Options
            </a>
            <a href="{{ route('admin.tickets.duedates') }}"
               class="nav-link {{ request()->routeIs('admin.tickets.duedates') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i> Edit Due Dates
            </a>
        @endif

    </nav>

    <div class="sidebar-footer">
        <a href="{{ route('dashboard') }}">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <form action="{{ route('logout') }}" method="POST" style="margin:0">
            @csrf
            <button type="submit" style="width:100%;display:flex;align-items:center;gap:8px;font-size:13px;color:#dc2626;background:none;border:none;cursor:pointer;padding:8px 10px;border-radius:6px;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>
</div>

<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <i class="fas fa-bars" style="font-size:18px;color:#9ca3af;cursor:pointer"></i>

        </div>
        <div class="topbar-right">
            @if(Auth::user()->role === 'admin')
                <span class="admin-badge"><i class="fas fa-shield-alt"></i> Admin</span>
            @elseif(Auth::user()->role === 'support')
                <span class="support-badge"><i class="fas fa-headset"></i> Support</span>
            @else
                <span class="user-badge-role"><i class="fas fa-user"></i> User</span>
            @endif

            {{-- Notification Bell (support only) --}}
            @if(Auth::user()->role === 'support')
            <div id="notif-wrapper">
                <button class="notif-btn" id="notif-bell-btn" onclick="toggleNotifDropdown()">
                    <i class="fas fa-bell"></i>
                    <span id="notif-badge"></span>
                </button>
                <div id="notif-dropdown">
                    <div class="notif-header">
                        <span>Notifications</span>
                        <button class="notif-mark-all" onclick="markAllRead()">Mark all as read</button>
                    </div>
                    <div id="notif-list">
                        <div class="notif-empty">Loading...</div>
                    </div>
                </div>
            </div>
            @endif

            <div class="user-badge">
                <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                <span>{{ Auth::user()->name }}</span>
            </div>
        </div>
    </div>

    <div class="content">
        @if(session('success'))
            <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</div>

@yield('scripts')

@if(Auth::user()->role === 'support')
<script>
let notifOpen = false;

function toggleNotifDropdown() {
    notifOpen = !notifOpen;
    document.getElementById('notif-dropdown').style.display = notifOpen ? 'block' : 'none';
    if (notifOpen) loadNotifications();
}

// Close when clicking outside
document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('notif-wrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        notifOpen = false;
        document.getElementById('notif-dropdown').style.display = 'none';
    }
});

function loadNotifications() {
    fetch('/notifications', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => renderNotifications(data.notifications ?? data))
    .catch(() => {
        document.getElementById('notif-list').innerHTML = '<div class="notif-empty">Failed to load.</div>';
    });
}

function fetchUnreadCount() {
    fetch('/notifications', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        const unread = data.unread_count ?? (data.notifications ?? data).filter(n => !n.is_read).length;
        const badge = document.getElementById('notif-badge');
        if (unread > 0) {
            badge.style.display = 'flex';
            badge.textContent = unread > 99 ? '99+' : unread;
        } else {
            badge.style.display = 'none';
        }
    })
    .catch(() => {});
}

function renderNotifications(data) {
    const list = document.getElementById('notif-list');
    if (!data.length) {
        list.innerHTML = '<div class="notif-empty">No notifications yet.</div>';
        return;
    }
    list.innerHTML = data.map(n => `
        <div class="notif-item ${n.is_read ? '' : 'unread'}" onclick="markRead(${n.id})">
            <div class="notif-item-title">${n.title}</div>
            <div class="notif-item-msg">${n.message}</div>
            <div class="notif-item-time">${n.created_at}</div>
        </div>
    `).join('');
}

function markRead(id) {
    fetch(`/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    }).then(() => {
        loadNotifications();
        fetchUnreadCount();
    });
}

function markAllRead() {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    }).then(() => {
        loadNotifications();
        fetchUnreadCount();
    });
}

// Initial count + auto-refresh every 30s
fetchUnreadCount();
setInterval(fetchUnreadCount, 30000);
</script>
@endif

</body>
</html>
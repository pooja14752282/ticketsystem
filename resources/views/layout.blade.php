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
        body { font-family: 'Segoe UI', sans-serif; background: #f1f3f6; display: flex; flex-direction: column; height: 100vh; overflow: hidden; }

        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; padding: 0 20px; height: 56px; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .admin-badge { background: #fef3c7; color: #92400e; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; }
        .support-badge { background: #dcfce7; color: #166534; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; }
        .user-badge-role { background: #dbeafe; color: #1d4ed8; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; }
        .topbar-right { display: flex; align-items: center; gap: 12px; }
        .notif-btn { display: flex; align-items: center; gap: 6px; font-size: 13px; color: #6b7280; cursor: pointer; padding: 6px 10px; border-radius: 6px; border: none; background: none; }
        .notif-btn:hover { background: #f3f4f6; }
        .user-badge { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #6b7280; }
        .avatar { width: 30px; height: 30px; border-radius: 50%; background: #fde68a; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; color: #92400e; }
        .dashboard-btn { display: flex; align-items: center; gap: 6px; font-size: 13px; color: #6b7280; text-decoration: none; padding: 6px 10px; border-radius: 6px; border: 1px solid #e5e7eb; background: #fff; }
        .dashboard-btn:hover { background: #f3f4f6; color: #111827; }
        .logout-btn { display: flex; align-items: center; gap: 6px; font-size: 13px; color: #dc2626; padding: 6px 10px; border-radius: 6px; border: none; background: none; cursor: pointer; }
        .logout-btn:hover { background: #fef2f2; }

        /* Notification Bell */
        #notif-wrapper { position: relative; }
        #notif-bell-btn { position: relative; }
        #notif-badge {
            display: none; position: absolute; top: 2px; right: 2px;
            background: #ef4444; color: #fff; font-size: 10px; font-weight: 700;
            border-radius: 50%; width: 17px; height: 17px;
            align-items: center; justify-content: center; pointer-events: none;
        }
        #notif-dropdown {
            display: none; position: absolute; right: 0; top: 44px;
            width: 320px; background: #fff; border: 1px solid #e5e7eb;
            border-radius: 10px; box-shadow: 0 8px 24px rgba(0,0,0,0.12); z-index: 999;
        }
        .notif-header { padding: 12px 16px; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; }
        .notif-header span { font-size: 13px; font-weight: 600; color: #111827; }
        .notif-mark-all { font-size: 11px; color: #1d4ed8; background: none; border: none; cursor: pointer; }
        .notif-mark-all:hover { text-decoration: underline; }
        #notif-list { max-height: 320px; overflow-y: auto; }
        .notif-item { padding: 12px 16px; border-bottom: 1px solid #f9fafb; cursor: pointer; transition: background 0.1s; }
        .notif-item:last-child { border-bottom: none; }
        .notif-item:hover { background: #f3f4f6; }
        .notif-item.unread { background: #eff6ff; }
        .notif-item.unread:hover { background: #dbeafe; }
        .notif-item-title { font-size: 13px; font-weight: 600; color: #111827; }
        .notif-item-msg { font-size: 12px; color: #6b7280; margin-top: 2px; }
        .notif-item-time { font-size: 11px; color: #9ca3af; margin-top: 4px; }
        .notif-empty { padding: 24px; text-align: center; color: #9ca3af; font-size: 13px; }

        .content { flex: 1; overflow-y: auto; padding: 20px; }

        .alert-success { background: #dcfce7; color: #166534; padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; border: 1px solid #bbf7d0; display: flex; align-items: center; gap: 8px; }
        .alert-error   { background: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; border: 1px solid #fecaca; display: flex; align-items: center; gap: 8px; }

        .ticket-tabs { display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
        .ticket-tab { display: flex; align-items: center; gap: 8px; padding: 10px 16px; background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; text-decoration: none; color: #6b7280; font-size: 13px; font-weight: 500; transition: all .15s ease; }
        .ticket-tab:hover { background: #f9fafb; color: #111827; }
        .ticket-tab.active { background: #dbeafe; color: #1d4ed8; border-color: #93c5fd; }
    </style>
    @yield('styles')
</head>
<body>

{{-- ══ TOPBAR ONLY (no sidebar) ══ --}}
<div class="topbar">
    <div class="topbar-left">
        <a href="{{ route('dashboard') }}" class="dashboard-btn">
            <i class="fas fa-home"></i> Dashboard
        </a>
    </div>
    <div class="topbar-right">
        @if(Auth::user()->role === 'admin')
            <span class="admin-badge"><i class="fas fa-shield-alt"></i> Admin</span>
        @elseif(Auth::user()->role === 'support')
            <span class="support-badge"><i class="fas fa-headset"></i> Support</span>
        @else
            <span class="user-badge-role"><i class="fas fa-user"></i> User</span>
        @endif

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

        
</div>

        
    </div>
</div>

{{-- ══ CONTENT ══ --}}
<div class="content">

    {{-- SHOW TABS ONLY FOR TICKET PAGES --}}
    @if(
        request()->routeIs('admin.tickets.*') ||
        request()->routeIs('ticketsystem.*') ||
        request()->routeIs('support.tickets')
    )

        {{-- TICKET TABS --}}
        <div class="ticket-tabs">

            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.tickets.index') }}"
                   class="ticket-tab {{ request()->routeIs('admin.tickets.index') ? 'active' : '' }}">
                    <i class="fas fa-list-ul"></i> All Tickets
                </a>

                <a href="{{ route('ticketsystem.assigned') }}"
                   class="ticket-tab {{ request()->routeIs('ticketsystem.assigned') ? 'active' : '' }}">
                    <i class="fas fa-user-check"></i> Assigned To Me
                </a>
            @endif

            @if(Auth::user()->role === 'support')
                <a href="{{ route('support.tickets') }}"
                   class="ticket-tab {{ request()->routeIs('support.tickets') ? 'active' : '' }}">
                    <i class="fas fa-user-check"></i> Assigned To Me
                </a>
            @endif

            <a href="{{ route('ticketsystem.my') }}"
               class="ticket-tab {{ request()->routeIs('ticketsystem.my') ? 'active' : '' }}">
                <i class="fas fa-ticket-alt"></i> My Tickets
            </a>

        </div>

    @endif

    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    @yield('content')

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

document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('notif-wrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        notifOpen = false;
        document.getElementById('notif-dropdown').style.display = 'none';
    }
});

function loadNotifications() {
    fetch('/notifications', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => renderNotifications(data.notifications ?? data))
        .catch(() => {
            document.getElementById('notif-list').innerHTML = '<div class="notif-empty">Failed to load.</div>';
        });
}

function fetchUnreadCount() {
    fetch('/notifications', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
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
    }).then(() => { loadNotifications(); fetchUnreadCount(); });
}

function markAllRead() {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    }).then(() => { loadNotifications(); fetchUnreadCount(); });
}

fetchUnreadCount();
setInterval(fetchUnreadCount, 30000);
</script>
@endif

</body>
</html>
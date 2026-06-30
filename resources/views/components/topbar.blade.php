<!-- TOPBAR -->
<header class="topbar">
    <div class="topbar-left">
        <h2>Ticket Dashboard</h2>
        <p>Overview of all support tickets and their status</p>
    </div>
    <div class="topbar-right" style="display:flex; align-items:center; gap:12px;">
    
        {{-- NOTIFICATION BELL --}}
        <div style="position:relative; display:inline-block;" id="notifDropdown">
            <div onclick="toggleNotifDropdown()" style="position:relative; cursor:pointer; width:34px; height:34px; border-radius:50%; display:flex; align-items:center; justify-content:center; background:#f3f4f6;">
                <i class="fas fa-bell" style="font-size:15px; color:#374151;"></i>
                <span id="notifCountBadge" style="display:none; position:absolute; top:-2px; right:-2px; background:#1d4ed8; color:#fff; font-size:10px; font-weight:700; border-radius:50%; min-width:16px; height:16px; line-height:16px; text-align:center; padding:0 3px;"></span>            </div>

            <div id="notifMenu" style="display:none; position:absolute; right:0; top:42px; background:#fff; border:1px solid #e5e7eb; border-radius:8px; width:300px; max-height:360px; overflow-y:auto; box-shadow:0 6px 16px rgba(0,0,0,0.08); z-index:1000;">
                <div style="padding:10px 12px; font-size:13px; font-weight:600; color:#111827; border-bottom:1px solid #f3f4f6;">
                    Notifications
                </div>
                <div id="notifList">
                    <div style="padding:14px 12px; font-size:12px; color:#9ca3af;">Loading...</div>
                </div>
            </div>
        </div>


        @if(Auth::user()->isAdmin())
    <span class="role-pill badge-admin">
        <i class="fas fa-shield-alt"></i> Admin
    </span>
@elseif(Auth::user()->isTicketSupportTeam())
    <span class="role-pill badge-support">
        <i class="fas fa-headset"></i> Support
    </span>
@else
    <span class="role-pill badge-user">
        <i class="fas fa-user"></i> User
    </span>
@endif

        <div style="position:relative; display:inline-block;" id="userDropdown">
            <div onclick="toggleDropdown()" style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                <div style="width:30px; height:30px; border-radius:50%; background:#dbeafe; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; color:#1d4ed8;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <span style="font-size:13px; color:000000; font-weight:500;">
                    {{ Auth::user()->name }}
                </span>
            </div>

            <div id="dropdownMenu" style="display:none; position:absolute; right:0; top:40px; background:#fff; border:1px solid #e5e7eb; border-radius:8px; width:180px; box-shadow:0 6px 16px rgba(0,0,0,0.08); z-index:1000;">
                <a href="{{ route('profile') }}" style="display:block; padding:10px; font-size:13px; color:000000; text-decoration:none;">
                    Profile
                </a>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   style="display:block; padding:10px; font-size:13px; color:#dc2626; text-decoration:none;">
                    Sign out
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </div>
        </div>

    </div>
</header>

<script>
function toggleNotifDropdown() {
    const menu = document.getElementById('notifMenu');
    const isOpening = menu.style.display !== 'block';
    menu.style.display = isOpening ? 'block' : 'none';
    if (isOpening) loadNotifications();
}

document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('notifDropdown');
    if (dropdown && !dropdown.contains(e.target)) {
        document.getElementById('notifMenu').style.display = 'none';
    }
});

function loadNotifications() {
    fetch('{{ route("notifications.index") }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        const list = document.getElementById('notifList');
        const badge = document.getElementById('notifCountBadge');
        const items = data.notifications || [];

        if (items.length === 0) {
            list.innerHTML = '<div style="padding:14px 12px; font-size:12px; color:#9ca3af;">No notifications</div>';
            badge.style.display = 'none';
            return;
        }

        badge.textContent = items.length;
        badge.style.display = 'block';

        list.innerHTML = items.map(n => `
            <div style="padding:10px 12px; border-bottom:1px solid #f3f4f6;">
                <div style="font-size:12px; font-weight:600; color:#111827;">${escapeHtml(n.title)}</div>
                <div style="font-size:12px; color:#6b7280; margin-top:2px;">${escapeHtml(n.message)}</div>
                <div style="font-size:11px; color:#9ca3af; margin-top:4px;">${n.created_at}</div>
            </div>
        `).join('');
    })
    .catch(() => {
        document.getElementById('notifList').innerHTML = '<div style="padding:14px 12px; font-size:12px; color:#dc2626;">Failed to load</div>';
    });
}

function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str ?? '';
    return div.innerHTML;
}

// initial badge load on page load (optional, light)
document.addEventListener('DOMContentLoaded', loadNotifications);
</script>
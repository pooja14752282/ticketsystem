<!-- TOPBAR -->
<header class="topbar">
    <div class="topbar-left">
        <h2>Ticket Dashboard</h2>
        <p>Overview of all support tickets and their status</p>
    </div>
    <div class="topbar-right" style="display:flex; align-items:center; gap:12px;">

        @if(Auth::user()->role === 'admin')
            <span class="role-pill badge-admin">
                <i class="fas fa-shield-alt"></i> Admin
            </span>
        @elseif(Auth::user()->role === 'support')
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
                <span style="font-size:13px; color:#374151; font-weight:500;">
                    {{ Auth::user()->name }}
                </span>
            </div>

            <div id="dropdownMenu" style="display:none; position:absolute; right:0; top:40px; background:#fff; border:1px solid #e5e7eb; border-radius:8px; width:180px; box-shadow:0 6px 16px rgba(0,0,0,0.08); z-index:1000;">
                <a href="{{ route('profile') }}" style="display:block; padding:10px; font-size:13px; color:#374151; text-decoration:none;">
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
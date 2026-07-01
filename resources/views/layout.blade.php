<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin — Ticket System')</title>

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')   
    @stack('styles')   
</head>

<body>

<div class="app-wrapper">

    {{-- SIDEBAR --}}
    @include('components.sidebar')

    {{-- MAIN AREA --}}
    <div class="main">

        {{-- TOPBAR --}}
        @include('components.topbar')

        {{-- PAGE CONTENT --}}
        <div class="content">
            @include('components.alerts')
            @include('components.ticket-tabs')
            @yield('content')
        </div>

    </div>

</div>

@stack('scripts')  {{-- ← removed duplicate --}}

<script>
function toggleDropdown() {
    const menu = document.getElementById('dropdownMenu');
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('userDropdown');
    if (dropdown && !dropdown.contains(e.target)) {
        document.getElementById('dropdownMenu').style.display = 'none';
    }
});
</script>

</body>
</html>
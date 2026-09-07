<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Administration')</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="admin-body">
        <div class="admin-shell">
            <aside class="admin-sidebar">
                <div class="admin-brand">
                    <a href="{{ route('home') }}">Portfolio</a>
                    <span>Admin</span>
                </div>

                <nav class="admin-nav" aria-label="Sidebar de navigation admin">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        Tableau de bord
                    </a>
                    <a href="{{ route('admin.profile') }}" class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                        Profil
                    </a>
                    <a href="{{ route('admin.messages.index') }}" class="nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                        Messages
                    </a>
                    <a href="{{ route('admin.projects.index') }}" class="nav-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                        Projets
                    </a>
                    <a href="{{ route('admin.technologies.index') }}" class="nav-link {{ request()->routeIs('admin.technologies.*') ? 'active' : '' }}">
                        Compétences
                    </a>
                    <a href="{{ route('admin.experiences.index') }}" class="nav-link {{ request()->routeIs('admin.experiences.*') ? 'active' : '' }}">
                        Expériences
                    </a>
                    <a href="{{ route('admin.formations.index') }}" class="nav-link {{ request()->routeIs('admin.formations.*') ? 'active' : '' }}">
                        Formations
                    </a>
                </nav>

                <div class="admin-user">
                    <span>{{ Auth::user()?->name ?? 'Admin' }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn">Déconnexion</button>
                    </form>
                </div>
            </aside>

            <main class="admin-content">
                @yield('content')
            </main>
        </div>
    </body>
</html>

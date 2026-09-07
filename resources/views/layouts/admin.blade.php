<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Administration')</title>
        <script>
            document.documentElement.classList.toggle('theme-light', localStorage.getItem('portfolio-theme') === 'light');
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="admin-body">
        <div class="admin-shell">
            <aside class="admin-sidebar">
                <div class="admin-brand">
                    <a href="{{ route('home') }}">Portfolio</a>
                    <div class="flex items-center gap-3">
                        <button type="button" class="theme-toggle" data-theme-toggle aria-label="Activer le thème clair" title="Activer le thème clair">
                            <svg class="theme-icon theme-icon-sun" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <circle cx="12" cy="12" r="4"></circle>
                                <path d="M12 2v2M12 20v2M4.93 4.93l1.42 1.42M17.65 17.65l1.42 1.42M2 12h2M20 12h2M4.93 19.07l1.42-1.42M17.65 6.35l1.42-1.42"></path>
                            </svg>
                            <svg class="theme-icon theme-icon-moon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"></path>
                            </svg>
                            <span class="sr-only" data-theme-label>Activer le thème clair</span>
                        </button>
                        <span>Admin</span>
                    </div>
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

<header class="sticky top-0 z-50 border-b border-slate-800/80 bg-slate-950/80 backdrop-blur-sm">
    <nav class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="text-lg font-semibold tracking-tight text-white">
            Mon Portfolio
        </a>

        <div class="hidden items-center gap-6 md:flex">
            <a href="{{ route('home') }}#experience" class="text-sm text-slate-300 transition hover:text-white">Expérience</a>
            <a href="{{ route('home') }}#competences" class="text-sm text-slate-300 transition hover:text-white">Technologies</a>
            <a href="{{ route('home') }}#formation" class="text-sm text-slate-300 transition hover:text-white">Formation</a>
            <a href="{{ route('home') }}#projects" class="text-sm text-slate-300 transition hover:text-white">Projets</a>
            <a href="{{ route('home') }}#contact" class="text-sm text-slate-300 transition hover:text-white">Contact</a>
        </div>

        <div class="flex items-center gap-3">
            @auth
                <a href="{{ route('admin.dashboard') }}" class="rounded-full border border-accent/60 bg-accent/10 px-4 py-2 text-sm font-medium text-accent transition hover:bg-accent/20">
                    Admin
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-full border border-slate-700 px-4 py-2 text-sm font-medium text-slate-200 transition hover:border-slate-500 hover:text-white">
                        Déconnexion
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="rounded-full bg-white px-4 py-2 text-sm font-medium text-slate-900 transition hover:bg-slate-200">
                    Connexion
                </a>
            @endauth
        </div>
    </nav>
</header>

<x-guest-layout>
    <x-auth-session-status class="mb-6 text-sm text-accent" :status="session('status')" />

    <div class="mb-8 space-y-2">
        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-accent">Espace sécurisé</p>
        <h1 class="text-2xl font-bold text-white sm:text-3xl">Bienvenue</h1>
        <p class="text-sm leading-6 text-slate-400">Connectez-vous pour accéder à votre espace d’administration.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div class="space-y-2">
            <x-input-label for="email" value="Adresse e-mail" class="text-sm font-medium text-slate-200" />
            <x-text-input
                id="email"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
                :aria-invalid="$errors->has('email') ? 'true' : 'false'"
                :aria-describedby="$errors->has('email') ? 'email-error' : null"
                class="mt-1 block w-full rounded-xl {{ $errors->has('email') ? '!border-rose-500 focus:!border-rose-500 focus:!ring-rose-500' : '!border-slate-700 focus:!border-accent focus:!ring-accent' }} bg-slate-950/70 px-4 py-3 text-sm text-white placeholder:text-slate-500 shadow-sm transition"
            />
            <x-input-error id="email-error" role="alert" :messages="$errors->get('email')" class="text-sm text-rose-400" />
        </div>

        <div class="space-y-2">
            <x-input-label for="password" value="Mot de passe" class="text-sm font-medium text-slate-200" />
            <x-text-input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                :aria-invalid="$errors->has('password') ? 'true' : 'false'"
                :aria-describedby="$errors->has('password') ? 'password-error' : null"
                class="mt-1 block w-full rounded-xl {{ $errors->has('password') ? '!border-rose-500 focus:!border-rose-500 focus:!ring-rose-500' : '!border-slate-700 focus:!border-accent focus:!ring-accent' }} bg-slate-950/70 px-4 py-3 text-sm text-white placeholder:text-slate-500 shadow-sm transition"
            />
            <x-input-error id="password-error" role="alert" :messages="$errors->get('password')" class="text-sm text-rose-400" />
        </div>

        <div class="flex items-center justify-between gap-4">
            <label for="remember_me" class="inline-flex items-center gap-2">
                <input id="remember_me" type="checkbox" class="h-4 w-4 shrink-0 rounded border-slate-600 bg-slate-950 text-accent shadow-sm focus:ring-accent" name="remember">
                <span class="text-sm text-slate-300">Se souvenir de moi</span>
            </label>

            @if (Route::has('password.request'))
                <a class="rounded text-sm text-accent hover:text-teal-300 focus:outline-none focus:ring-2 focus:ring-accent" href="{{ route('password.request') }}">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center rounded-xl border border-accent/30 bg-accent px-4 py-3 text-sm font-semibold normal-case tracking-normal text-slate-950 shadow-lg shadow-accent/10 transition hover:bg-teal-300 focus:bg-teal-300 focus:ring-accent active:bg-teal-400">
                Se connecter
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

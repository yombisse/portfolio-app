@extends('layouts.admin')

@section('title', 'Profil du portfolio')

@section('content')
    @php
        $socialValues = old('reseaux', collect($profile->reseaux_sociaux ?? [])->map(function ($social, $key) {
            return is_array($social)
                ? ['nom' => $social['nom'] ?? $key, 'url' => $social['url'] ?? '']
                : ['nom' => ucfirst($key), 'url' => $social];
        })->values()->all());
        $socialValues = is_array($socialValues) ? array_values($socialValues) : [];
        if ($socialValues === []) {
            $socialValues = [['nom' => '', 'url' => '']];
        }
    @endphp

    <div class="admin-page-header">
        <div>
            <p class="eyebrow eyebrow-admin">Profil</p>
            <h1>Modifier le profil principal</h1>
        </div>
        @if($storedProfile)
            <span class="badge">Profil enregistré</span>
        @else
            <span class="badge">Données de démonstration</span>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert border-rose-500/40 bg-rose-500/10 text-rose-200">
            <p class="font-semibold">Le profil n'a pas pu être enregistré.</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ $storedProfile ? route('admin.profile.update') : route('admin.profile.store') }}" enctype="multipart/form-data" class="contact-form">
        @csrf
        @if($storedProfile)
            @method('PUT')
        @endif

        <div class="form-grid">
            <div>
                <label for="nom">Nom *</label>
                <input id="nom" name="nom" value="{{ old('nom', $profile->nom) }}" required>
            </div>
            <div>
                <label for="titre">Titre *</label>
                <input id="titre" name="titre" value="{{ old('titre', $profile->titre) }}" required>
            </div>
        </div>

        <div>
            <label for="bio">Biographie *</label>
            <textarea id="bio" name="bio" required>{{ old('bio', $profile->bio) }}</textarea>
        </div>

        <div class="form-grid">
            <div>
                <label for="focus">Focus</label>
                <textarea id="focus" name="focus">{{ old('focus', $profile->focus) }}</textarea>
            </div>
            <div>
                <label for="work_style">Style de travail</label>
                <textarea id="work_style" name="work_style">{{ old('work_style', $profile->work_style) }}</textarea>
            </div>
        </div>

        <div class="form-grid">
            <div>
                <label for="localisation">Localisation *</label>
                <input id="localisation" name="localisation" value="{{ old('localisation', $profile->localisation) }}" required>
            </div>
            <div>
                <label for="email">Email *</label>
                <input id="email" name="email" type="email" value="{{ old('email', $profile->email) }}" required>
            </div>
            <div>
                <label for="telephone">Téléphone</label>
                <input id="telephone" name="telephone" value="{{ old('telephone', $profile->telephone) }}">
            </div>
            <div>
                <label for="photo">Photo de profil</label>
                <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp">
                <p class="mt-2 text-xs text-slate-400">JPG, PNG ou WebP, 4 Mo maximum.</p>
                @if($profile->photo)
                    <a href="{{ str_starts_with($profile->photo, 'http') ? $profile->photo : asset('storage/' . ltrim($profile->photo, '/')) }}" target="_blank" rel="noopener" class="mt-2 inline-block text-sm text-accent hover:underline">Voir la photo actuelle</a>
                @endif
            </div>
        </div>

        <div x-data="{ networks: @js($socialValues), max: 10 }" class="dynamic-field-group">
            <div class="field-heading">
                <div>
                    <h2 class="mb-1 text-lg font-semibold text-white">Réseaux sociaux</h2>
                    <p class="field-help">Ajoutez les réseaux dont vous avez besoin.</p>
                </div>
                <button type="button" class="btn btn-small btn-secondary" @click="if (networks.length < max) networks.push({ nom: '', url: '' })" :disabled="networks.length >= max">+ Ajouter</button>
            </div>
            <div class="dynamic-list">
                <template x-for="(network, index) in networks" :key="index">
                    <div class="dynamic-row social-row">
                        <input type="text" :name="`reseaux[${index}][nom]`" x-model="network.nom" maxlength="50" placeholder="Nom : GitHub" :required="index === 0">
                        <input type="url" :name="`reseaux[${index}][url]`" x-model="network.url" placeholder="https://..." :required="network.nom !== ''">
                        <button type="button" class="icon-button" title="Supprimer ce réseau" @click="networks.length > 1 ? networks.splice(index, 1) : networks[index] = { nom: '', url: '' }">×</button>
                    </div>
                </template>
            </div>
            <p class="field-help">10 réseaux maximum.</p>
            @error('reseaux.*.nom')<small class="error-message">{{ $message }}</small>@enderror
            @error('reseaux.*.url')<small class="error-message">{{ $message }}</small>@enderror
        </div>

        <div>
            <label for="cv">CV</label>
            <input id="cv" name="cv" type="file" accept="application/pdf">
            <p class="mt-2 text-xs text-slate-400">PDF uniquement, 8 Mo maximum.</p>
            @if($profile->cv)
                <a href="{{ str_starts_with($profile->cv, 'http') ? $profile->cv : asset('storage/' . ltrim($profile->cv, '/')) }}" target="_blank" rel="noopener" class="mt-2 inline-block text-sm text-accent hover:underline">Télécharger le CV actuel</a>
            @endif
        </div>

        <div class="flex flex-wrap items-center gap-3 border-t border-slate-800 pt-5">
            <button type="submit" class="btn btn-primary">Enregistrer le profil</button>
            @if($storedProfile)
                <button type="submit" form="delete-profile-form" class="btn border border-rose-500/50 text-rose-300 hover:bg-rose-500/10">Supprimer le profil</button>
            @endif
        </div>
    </form>

    @if($storedProfile)
        <form id="delete-profile-form" method="POST" action="{{ route('admin.profile.destroy') }}" onsubmit="return confirm('Supprimer le profil enregistré ?');">
            @csrf
            @method('DELETE')
        </form>
    @endif
@endsection

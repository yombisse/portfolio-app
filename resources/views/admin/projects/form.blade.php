@extends('layouts.admin')

@section('title', $project->exists ? 'Modifier le projet' : 'Nouveau projet')

@section('content')
    @php
        $featureValues = old('fonctionnalites', $project->fonctionnalites ?? []);
        $featureValues = is_array($featureValues) ? array_values($featureValues) : [];
        if ($featureValues === []) {
            $featureValues = [''];
        }
        $selectedCompetences = old('competences', $project->competences?->pluck('id')->all() ?? []);
        $selectedCompetences = is_array($selectedCompetences) ? $selectedCompetences : [];
    @endphp

    <div class="admin-page-header">
        <div>
            <p class="eyebrow eyebrow-admin">Projets</p>
            <h1>{{ $project->exists ? 'Modifier le projet' : 'Créer un projet' }}</h1>
        </div>
    </div>

    @if($errors->any())
        <div class="alert border-rose-500/40 bg-rose-500/10 text-rose-200">
            <p class="font-semibold">Le projet n'a pas pu être enregistré.</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ $project->exists ? route('admin.projects.update', $project) : route('admin.projects.store') }}" enctype="multipart/form-data" class="contact-form">
        @csrf
        @if($project->exists)
            @method('PUT')
        @endif

        <div class="form-grid">
            <div>
                <label for="titre">Titre *</label>
                <input id="titre" name="titre" value="{{ old('titre', $project->titre) }}" required>
            </div>
            <div>
                <label for="role">Rôle *</label>
                <input id="role" name="role" value="{{ old('role', $project->role) }}" required>
            </div>
            <div>
                <label for="date_projet">Date du projet *</label>
                <input id="date_projet" name="date_projet" type="date" class="date-picker-only" max="{{ now()->format('Y-m-d') }}" value="{{ old('date_projet', $project->date_projet?->format('Y-m-d')) }}" required>
                @error('date_projet')<small class="error-message">{{ $message }}</small>@enderror
            </div>
        </div>

        <div>
            <label for="description">Description *</label>
            <textarea id="description" name="description" rows="6" required>{{ old('description', $project->description) }}</textarea>
        </div>

        <div class="form-grid">
            <div>
                <label for="github_url">URL GitHub</label>
                <input id="github_url" name="github_url" type="url" value="{{ old('github_url', $project->github_url) }}" placeholder="https://github.com/...">
            </div>
            <div>
                <label for="url_projet">URL du projet</label>
                <input id="url_projet" name="url_projet" type="url" value="{{ old('url_projet', $project->url_projet) }}" placeholder="https://...">
            </div>
        </div>

        <div>
            <label for="image_principale">Image principale</label>
            <input id="image_principale" name="image_principale" type="file" accept="image/jpeg,image/png,image/webp">
            <p class="mt-2 text-xs text-slate-400">JPG, PNG ou WebP, 4 Mo maximum. Obligatoire pour publier un nouveau projet.</p>
            @if($project->image_principale)
                <a href="{{ str_starts_with($project->image_principale, 'http') ? $project->image_principale : asset('storage/' . ltrim($project->image_principale, '/')) }}" target="_blank" rel="noopener" class="mt-2 inline-block text-sm text-accent hover:underline">Voir l'image actuelle</a>
            @endif
        </div>

        <div class="form-grid">
            <div x-data="{ features: @js($featureValues), max: 10 }" class="dynamic-field-group">
                <div class="field-heading">
                    <div>
                        <label>Fonctionnalités</label>
                        <p class="field-help">Ajoutez les fonctionnalités une par une.</p>
                    </div>
                    <button type="button" class="btn btn-small btn-secondary" @click="if (features.length < max) features.push('')" :disabled="features.length >= max">+ Ajouter</button>
                </div>
                <div class="dynamic-list">
                    <template x-for="(feature, index) in features" :key="index">
                        <div class="dynamic-row">
                            <input type="text" name="fonctionnalites[]" x-model="features[index]" maxlength="150" placeholder="Ex. Authentification sécurisée" :required="index === 0">
                            <button type="button" class="icon-button" title="Supprimer cette fonctionnalité" @click="features.length > 1 ? features.splice(index, 1) : features[index] = ''">×</button>
                        </div>
                    </template>
                </div>
                <p class="field-help">10 fonctionnalités maximum.</p>
                @error('fonctionnalites.*')<small class="error-message">{{ $message }}</small>@enderror
                @error('fonctionnalites')<small class="error-message">{{ $message }}</small>@enderror
            </div>

            <div>
                <label for="captures">Captures d'écran</label>
                <input id="captures" name="captures[]" type="file" accept="image/jpeg,image/png,image/webp" multiple>
                <p class="field-help">Sélectionnez plusieurs images à la fois, 10 captures maximum au total, 4 Mo par image.</p>
                @if($project->captures)
                    <div class="capture-preview-grid">
                        @foreach($project->captures as $capture)
                            <img src="{{ str_starts_with($capture, 'http') ? $capture : asset('storage/' . ltrim($capture, '/')) }}" alt="Capture de {{ $project->titre }}" class="capture-preview">
                        @endforeach
                    </div>
                @endif
                @error('captures.*')<small class="error-message">{{ $message }}</small>@enderror
                @error('captures')<small class="error-message">{{ $message }}</small>@enderror
            </div>
        </div>

        <div x-data="{ selected: @js($selectedCompetences) }">
            <div class="field-heading">
                <div>
                    <p class="mb-1 text-sm font-semibold text-white">Technologies associées *</p>
                    <p class="field-help"><span x-text="selected.length"></span> sélectionnée(s). Choisissez au moins une technologie.</p>
                </div>
            </div>
            <div class="competence-grid">
                @foreach($competences as $competence)
                    <label class="competence-option">
                        <input type="checkbox" name="competences[]" value="{{ $competence->id }}" x-model="selected" @checked(in_array($competence->id, $selectedCompetences))>
                        {{ $competence->nom }}
                    </label>
                @endforeach
            </div>
            @if($competences->isEmpty())
                <p class="field-help">Aucune technologie n'est disponible. Ajoutez-en une avant d'enregistrer un projet.</p>
            @endif
            @error('competences')<small class="error-message">{{ $message }}</small>@enderror
            @error('competences.*')<small class="error-message">{{ $message }}</small>@enderror
        </div>

        <div class="publish-toggle">
            <label class="publish-toggle-label" for="publie">
                <input id="publie" type="checkbox" name="publie" value="1" @checked(old('publie', $project->publie))>
                <span class="publish-toggle-track" aria-hidden="true"><span></span></span>
                <span>
                    <strong>Publier ce projet</strong>
                    <small>Le projet sera visible sur la page d'accueil.</small>
                </span>
            </label>
        </div>

        <div class="flex flex-wrap gap-3 border-t border-slate-800 pt-5">
            <button type="submit" class="btn btn-primary">Enregistrer le projet</button>
            <a href="{{ route('admin.projects.index') }}" class="btn border border-slate-700 text-slate-200 hover:border-slate-500 hover:text-white">Annuler</a>
        </div>
    </form>
@endsection
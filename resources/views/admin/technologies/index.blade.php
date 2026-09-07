@extends('layouts.admin')

@section('title', 'Compétences')

@section('content')
    <div class="admin-page-header">
        <div>
            <p class="eyebrow eyebrow-admin">Contenu</p>
            <h1>Compétences</h1>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert border-rose-500/40 bg-rose-500/10 text-rose-200">
            <ul class="list-disc space-y-1 pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="admin-panel">
        <h2 class="text-xl">Ajouter une compétence</h2>
        <form method="POST" action="{{ route('admin.technologies.store') }}" class="mt-5 grid gap-4 sm:grid-cols-[minmax(0,1fr)_220px_220px_auto] sm:items-end" x-data="{ category: '' }">
            @csrf
            <div>
                <label for="nom">Nom de la compétence *</label>
                <input id="nom" name="nom" placeholder="React, Laravel, Leadership..." required>
            </div>
            <div>
                <label for="categorie">Catégorie *</label>
                <select id="categorie" name="categorie" x-model="category" required>
                    <option value="">Choisir une catégorie</option>
                    <option value="technologie">Technologie</option>
                    <option value="aptitude">Aptitude</option>
                </select>
            </div>
            <div>
                <label for="niveau">Niveau</label>
                <select id="niveau" name="niveau" x-bind:disabled="category !== 'technologie'" x-bind:required="category === 'technologie'">
                    <option value="">Choisir un niveau</option>
                    <option value="débutant">Débutant</option>
                    <option value="intermédiaire">Intermédiaire</option>
                    <option value="avancé">Avancé</option>
                    <option value="expert">Expert</option>
                </select>
                <p class="field-help">Obligatoire pour une technologie, vide pour une aptitude.</p>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </section>

    <section class="admin-panel mt-6">
        <div class="field-heading">
            <div>
                <h2 class="text-xl">Compétences enregistrées</h2>
                <p class="field-help">Tri alphabétique à l'intérieur de chaque catégorie.</p>
            </div>
            <form method="GET" action="{{ route('admin.technologies.index') }}" class="category-filter">
                <label for="filter-categorie">Trier par catégorie</label>
                <select id="filter-categorie" name="categorie" onchange="this.form.submit()">
                    <option value="" @selected(!$category)>Toutes les catégories</option>
                    <option value="technologie" @selected($category === 'technologie')>Technologies</option>
                    <option value="aptitude" @selected($category === 'aptitude')>Aptitudes</option>
                </select>
            </form>
        </div>
        <div class="mt-5 space-y-3">
            @forelse($competences as $competence)
                <div class="technology-row" x-data="{ editing: false }">
                    <div x-show="!editing" class="flex min-w-0 flex-1 items-center gap-3">
                        <strong class="truncate">{{ $competence->nom }}</strong>
                        <span class="badge">{{ ucfirst($competence->categorie) }}</span>
                        @if($competence->niveau)<span class="badge">{{ ucfirst($competence->niveau) }}</span>@endif
                        <span class="text-xs text-slate-400">{{ $competence->projects_count }} projet(s)</span>
                    </div>
                    <form x-show="editing" method="POST" action="{{ route('admin.technologies.update', $competence) }}" class="technology-edit-form">
                        @csrf
                        @method('PUT')
                        <input name="nom" value="{{ $competence->nom }}" required>
                        <select name="categorie" required>
                            <option value="technologie" @selected($competence->categorie === 'technologie')>Technologie</option>
                            <option value="aptitude" @selected($competence->categorie === 'aptitude')>Aptitude</option>
                        </select>
                        <select name="niveau" @disabled($competence->categorie === 'aptitude') @required($competence->categorie === 'technologie')>
                            <option value="">{{ $competence->categorie === 'aptitude' ? 'Sans niveau' : 'Choisir un niveau' }}</option>
                            @foreach(['débutant', 'intermédiaire', 'avancé', 'expert'] as $level)
                                <option value="{{ $level }}" @selected($competence->niveau === $level)>{{ ucfirst($level) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary btn-small">Enregistrer</button>
                        <button type="button" class="btn btn-small border border-slate-700 text-slate-300" @click="editing = false">Annuler</button>
                    </form>
                    <div x-show="!editing" class="flex shrink-0 gap-2">
                        <button type="button" class="btn btn-small btn-secondary" @click="editing = true">Modifier</button>
                        <form method="POST" action="{{ route('admin.technologies.destroy', $competence) }}" onsubmit="return confirm('Supprimer cette compétence ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-small border border-rose-500/50 text-rose-300">Supprimer</button>
                        </form>
                    </div>
                </div>
            @empty
                <p>Aucune compétence enregistrée.</p>
            @endforelse
        </div>
    </section>
@endsection
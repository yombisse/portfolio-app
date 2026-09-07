@extends('layouts.admin')

@section('title', $formation->exists ? 'Modifier la formation' : 'Nouvelle formation')

@section('content')
    <div class="admin-page-header">
        <div>
            <p class="eyebrow eyebrow-admin">Formations</p>
            <h1>{{ $formation->exists ? 'Modifier la formation' : 'Ajouter une formation' }}</h1>
        </div>
    </div>

    @if($errors->any())
        <div class="alert border-rose-500/40 bg-rose-500/10 text-rose-200">
            <p class="font-semibold">La formation n’a pas pu être enregistrée.</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ $formation->exists ? route('admin.formations.update', $formation) : route('admin.formations.store') }}" enctype="multipart/form-data" class="contact-form">
        @csrf
        @if($formation->exists) @method('PUT') @endif

        <div class="form-grid">
            <div>
                <label for="nom">Nom de la formation *</label>
                <input id="nom" name="nom" value="{{ old('nom', $formation->nom) }}" placeholder="Master Informatique" required>
            </div>
            <div>
                <label for="institut">Institut *</label>
                <input id="institut" name="institut" value="{{ old('institut', $formation->institut) }}" placeholder="Université ou école" required>
            </div>
            <div>
                <label for="domaine">Domaine</label>
                <input id="domaine" name="domaine" value="{{ old('domaine', $formation->domaine) }}" placeholder="Développement logiciel">
            </div>
            <div>
                <label for="diplome">Diplôme</label>
                <input id="diplome" name="diplome" value="{{ old('diplome', $formation->diplome) }}" placeholder="Master, Licence...">
            </div>
            <div>
                <label for="date_debut">Date de début *</label>
                <input id="date_debut" name="date_debut" type="date" class="date-picker-only" max="{{ now()->format('Y-m-d') }}" value="{{ old('date_debut', $formation->date_debut?->format('Y-m-d')) }}" required>
            </div>
            <div>
                <label for="date_fin">Date de fin</label>
                <input id="date_fin" name="date_fin" type="date" class="date-picker-only" max="{{ now()->format('Y-m-d') }}" value="{{ old('date_fin', $formation->date_fin?->format('Y-m-d')) }}">
                <p class="field-help">Laissez vide si la formation est en cours.</p>
            </div>
        </div>

        <div>
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="5" placeholder="Décrivez brièvement la formation">{{ old('description', $formation->description) }}</textarea>
        </div>

        <div>
            <label for="justificatif">Justificatif PDF</label>
            <input id="justificatif" name="justificatif" type="file" accept="application/pdf">
            <p class="field-help">PDF uniquement, 8 Mo maximum.</p>
            @if($formation->justificatif)
                <a href="{{ str_starts_with($formation->justificatif, 'http') ? $formation->justificatif : asset('storage/' . ltrim($formation->justificatif, '/')) }}" target="_blank" rel="noopener" class="mt-2 inline-block text-sm text-accent hover:underline">Voir le justificatif actuel</a>
            @endif
        </div>

        <div class="flex flex-wrap gap-3 border-t border-slate-800 pt-5">
            <button type="submit" class="btn btn-primary">Enregistrer la formation</button>
            <a href="{{ route('admin.formations.index') }}" class="btn border border-slate-700 text-slate-200">Annuler</a>
        </div>
    </form>
@endsection

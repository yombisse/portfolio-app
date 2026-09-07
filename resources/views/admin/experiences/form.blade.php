@extends('layouts.admin')

@section('title', $experience->exists ? 'Modifier l’expérience' : 'Nouvelle expérience')

@section('content')
    @php
        $bilanValues = old('bilan', $experience->bilan ?? []);
        $bilanValues = is_array($bilanValues) ? array_values($bilanValues) : [];
        if ($bilanValues === []) {
            $bilanValues = [''];
        }
    @endphp

    <div class="admin-page-header">
        <div>
            <p class="eyebrow eyebrow-admin">Expériences</p>
            <h1>{{ $experience->exists ? 'Modifier l’expérience' : 'Ajouter une expérience' }}</h1>
        </div>
    </div>

    @if($errors->any())
        <div class="alert border-rose-500/40 bg-rose-500/10 text-rose-200">
            <p class="font-semibold">L’expérience n’a pas pu être enregistrée.</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ $experience->exists ? route('admin.experiences.update', $experience) : route('admin.experiences.store') }}" enctype="multipart/form-data" class="contact-form">
        @csrf
        @if($experience->exists) @method('PUT') @endif

        <div class="form-grid">
            <div>
                <label for="titre">Titre du poste *</label>
                <input id="titre" name="titre" value="{{ old('titre', $experience->titre) }}" required>
            </div>
            <div>
                <label for="entreprise">Entreprise *</label>
                <input id="entreprise" name="entreprise" value="{{ old('entreprise', $experience->entreprise) }}" required>
            </div>
            <div>
                <label for="lieu">Lieu</label>
                <input id="lieu" name="lieu" value="{{ old('lieu', $experience->lieu) }}" placeholder="Paris, France ou Remote">
            </div>
            <div>
                <label for="contact_entreprise">Contact de l’entreprise</label>
                <input id="contact_entreprise" name="contact_entreprise" type="email" value="{{ old('contact_entreprise', $experience->contact_entreprise) }}">
            </div>
            <div>
                <label for="date_debut">Date de début *</label>
                <input id="date_debut" name="date_debut" type="date" class="date-picker-only" max="{{ now()->format('Y-m-d') }}" value="{{ old('date_debut', $experience->date_debut?->format('Y-m-d')) }}" required>
            </div>
            <div>
                <label for="date_fin">Date de fin</label>
                <input id="date_fin" name="date_fin" type="date" class="date-picker-only" max="{{ now()->format('Y-m-d') }}" value="{{ old('date_fin', $experience->date_fin?->format('Y-m-d')) }}">
                <p class="field-help">Laissez vide si cette expérience est en cours.</p>
            </div>
        </div>

        <div x-data="{ items: @js($bilanValues), max: 10 }" class="dynamic-field-group">
            <div class="field-heading">
                <div>
                    <label>Bilan de l’expérience</label>
                    <p class="field-help">Ajoutez les réalisations une par une.</p>
                </div>
                <button type="button" class="btn btn-small btn-secondary" @click="if (items.length < max) items.push('')" :disabled="items.length >= max">+ Ajouter</button>
            </div>
            <div class="dynamic-list">
                <template x-for="(item, index) in items" :key="index">
                    <div class="dynamic-row">
                        <input type="text" name="bilan[]" x-model="items[index]" maxlength="250" placeholder="Ex. Mise en place d’une nouvelle architecture">
                        <button type="button" class="icon-button" title="Supprimer cet élément" @click="items.length > 1 ? items.splice(index, 1) : items[index] = ''">×</button>
                    </div>
                </template>
            </div>
            <p class="field-help">10 éléments maximum.</p>
        </div>

        <div>
            <label for="justificatif">Justificatif PDF</label>
            <input id="justificatif" name="justificatif" type="file" accept="application/pdf">
            <p class="field-help">PDF uniquement, 8 Mo maximum.</p>
            @if($experience->justificatif)
                <a href="{{ str_starts_with($experience->justificatif, 'http') ? $experience->justificatif : asset('storage/' . ltrim($experience->justificatif, '/')) }}" target="_blank" rel="noopener" class="mt-2 inline-block text-sm text-accent hover:underline">Voir le justificatif actuel</a>
            @endif
        </div>

        <div class="flex flex-wrap gap-3 border-t border-slate-800 pt-5">
            <button type="submit" class="btn btn-primary">Enregistrer l’expérience</button>
            <a href="{{ route('admin.experiences.index') }}" class="btn border border-slate-700 text-slate-200">Annuler</a>
        </div>
    </form>
@endsection

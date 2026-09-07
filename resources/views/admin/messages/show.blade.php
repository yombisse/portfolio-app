@extends('layouts.admin')

@section('title', 'Message de ' . $message->nom)

@section('content')
    <div class="admin-page-header">
        <div>
            <p class="eyebrow eyebrow-admin">Message reçu</p>
            <h1>{{ $message->sujet }}</h1>
        </div>
        <span class="badge">Lu</span>
    </div>

    <article class="admin-panel">
        <div class="message-head">
            <strong>{{ $message->nom }}</strong>
            <span>{{ $message->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <p class="message-email">{{ $message->email }}</p>
        <div class="mt-8 whitespace-pre-line border-t border-slate-800 pt-6 text-slate-200">{{ $message->contenu }}</div>
        <div class="mt-8 flex flex-wrap gap-3 border-t border-slate-800 pt-6">
            <a href="mailto:{{ $message->email }}?subject=Re: {{ rawurlencode($message->sujet) }}" class="btn btn-primary">Répondre par email</a>
            <a href="{{ route('admin.dashboard') }}#messages" class="btn border border-slate-700 text-slate-200 hover:border-slate-500 hover:text-white">Retour aux messages</a>
        </div>
    </article>
@endsection
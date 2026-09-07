@extends('layouts.admin')

@section('title', 'Administration')

@section('content')
    <div class="admin-page-header">
        <div>
            <p class="eyebrow eyebrow-admin">Dashboard</p>
            <h1>Administration du portfolio</h1>
        </div>
        <span class="badge">{{ $unreadCount }} non lus</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <section id="messages" class="admin-panel">
        <div class="field-heading">
            <div>
                <h2>Derniers messages non lus</h2>
                <p class="field-help">Les trois messages non lus les plus récents apparaissent ici.</p>
            </div>
            <a href="{{ route('admin.messages.index') }}" class="btn btn-small btn-secondary">Voir tous les messages</a>
        </div>

        @if($messages->isEmpty())
            <p>Aucun message non lu.</p>
        @else
            <div class="messages-list">
                @foreach($messages as $message)
                    <article class="message-item {{ $message->lu ? '' : 'message-unread' }}">
                        <div class="message-head">
                            <strong>{{ $message->nom }}</strong>
                            <span>{{ $message->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <p class="message-email">{{ $message->email }}</p>
                        <p class="message-subject">{{ $message->sujet }}</p>
                        <p class="line-clamp-2">{{ $message->contenu }}</p>
                        <div class="mt-4 flex flex-wrap items-center gap-3">
                            <a href="{{ route('admin.messages.show', $message) }}" class="btn btn-primary">Ouvrir le message</a>
                            <form method="POST" action="{{ route('admin.messages.read', $message) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn border border-slate-700 text-slate-200 hover:border-accent hover:text-accent">Marquer comme lu</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection

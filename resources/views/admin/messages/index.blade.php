@extends('layouts.admin')

@section('title', 'Messages')

@section('content')
    <div class="admin-page-header">
        <div>
            <p class="eyebrow eyebrow-admin">Contact</p>
            <h1>Messages reçus</h1>
        </div>
        <span class="badge">{{ $messages->count() }} message(s)</span>
    </div>

    <section class="admin-panel">
        @if($messages->isEmpty())
            <p>Aucun message pour le moment.</p>
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
                            @if(! $message->lu)
                                <form method="POST" action="{{ route('admin.messages.read', $message) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn border border-slate-700 text-slate-200 hover:border-accent hover:text-accent">Marquer comme lu</button>
                                </form>
                            @else
                                <span class="badge">Lu</span>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
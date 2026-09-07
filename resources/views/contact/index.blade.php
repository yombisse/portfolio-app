@extends('layouts.app')

@section('title', 'Contact')

@section('content')
    <section class="section">
        <div class="container narrow-container">
            <p class="section-label">CONTACT</p>
            <h1>Contact</h1>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('contact.store') }}" method="POST" class="contact-form">
                @csrf

                <div class="form-grid">
                    <div>
                        <label for="nom">Nom</label>
                        <input id="nom" name="nom" type="text" value="{{ old('nom') }}">
                        @error('nom')
                            <small class="error-message">{{ $message }}</small>
                        @enderror
                    </div>

                    <div>
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}">
                        @error('email')
                            <small class="error-message">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="sujet">Sujet</label>
                    <input id="sujet" name="sujet" type="text" value="{{ old('sujet') }}">
                    @error('sujet')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>

                <div>
                    <label for="contenu">Message</label>
                    <textarea id="contenu" name="contenu" rows="6">{{ old('contenu') }}</textarea>
                    @error('contenu')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Send message ↗</button>
            </form>
        </div>
    </section>
@endsection

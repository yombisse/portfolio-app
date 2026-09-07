@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
    <section class="hero personal-hero">
        <div class="container hero-grid">
            <div>
                <p class="section-label">SOFTWARE DEVELOPER PORTFOLIO</p>
                @if($profile)
                    @php
                        $cvUrl = null;
                        if ($profile->cv) {
                            $cvUrl = str_starts_with($profile->cv, 'http')
                                ? $profile->cv
                                : (str_starts_with(ltrim($profile->cv, '/'), 'profiles/')
                                    ? asset('storage/' . ltrim($profile->cv, '/'))
                                    : asset(ltrim($profile->cv, '/')));
                        }
                    @endphp
                    <p class="eyebrow">Portfolio personnel</p>
                    <h1>{{ $profile->titre }}</h1>
                    <p class="lead">{{ $profile->bio }}</p>

                    <div class="cta-row">
                        <a href="#contact" class="btn btn-primary">OPEN TO OPPORTUNITIES</a>
                        <a href="#projects" class="btn btn-secondary">Voir mes projets</a>
                        @if($cvUrl)
                            <a href="{{ $cvUrl }}" target="_blank" rel="noopener" class="btn btn-secondary">Prévisualiser mon CV</a>
                            <a href="{{ $cvUrl }}" download class="btn btn-secondary">Télécharger mon CV</a>
                        @endif
                    </div>

                    <ul class="meta-list">
                        <li><strong>Nom :</strong> {{ $profile->nom }}</li>
                        <li><strong>Localisation :</strong> {{ $profile->localisation }}</li>
                        <li><strong>Email :</strong> <a href="mailto:{{ $profile->email }}">{{ $profile->email }}</a></li>
                    </ul>

                    @if($profile->reseaux_sociaux)
                        <div class="social-links" aria-label="Réseaux sociaux">
                            @foreach($profile->reseaux_sociaux as $key => $social)
                                @php
                                    $socialName = is_array($social) ? ($social['nom'] ?? $key) : ucfirst($key);
                                    $socialUrl = is_array($social) ? ($social['url'] ?? null) : $social;
                                @endphp
                                @if($socialUrl)
                                    <a href="{{ $socialUrl }}" target="_blank" rel="noopener noreferrer" class="social-link">
                                        {{ $socialName }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @endif
                @else
                    <p class="eyebrow">Portfolio</p>
                    <h1>Profil à compléter</h1>
                    <p class="lead">Le profil du portfolio n’est pas encore renseigné.</p>
                @endif
            </div>

            <div class="card profile-card">
                @if($profile && $profile->photo)
                    <img src="{{ str_starts_with($profile->photo, 'http') ? $profile->photo : asset('storage/' . ltrim($profile->photo, '/')) }}" alt="Photo de {{ $profile->nom }}" class="profile-image">
                @else
                    <div class="placeholder-image">Photo</div>
                @endif
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <p class="section-label">ABOUT</p>
            <h2>À propos</h2>

            <div class="about-grid">
                <div>
                    @if($profile && $profile->bio)
                        <p class="lead">{{ $profile->bio }}</p>
                    @else
                        <p class="lead">[À REMPLACER — introduction personnelle]</p>
                    @endif
                </div>

                <div class="space-y-4">
                    <div class="about-block">
                        <div class="flex items-start">
                            <span class="about-bullet"></span>
                            <div>
                                <h3 class="text-sm font-semibold">FOCUS</h3>
                                @if($profile && $profile->focus)
                                    <p>{{ $profile->focus }}</p>
                                @else
                                    <p>[À REMPLACER — focus]</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="about-block">
                        <div class="flex items-start">
                            <span class="about-bullet"></span>
                            <div>
                                <h3 class="text-sm font-semibold">WORK STYLE</h3>
                                @if($profile && $profile->work_style)
                                    <p>{{ $profile->work_style }}</p>
                                @else
                                    <p>[À REMPLACER — work style]</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($competences->isNotEmpty())
        <section id="competences" class="section">
            <div class="container">
                <p class="section-label">SKILLS</p>
                <h2>Technologies</h2>
                <div class="skill-grid">
                    @foreach($competences as $competence)
                        <div class="skill-card">
                            <span class="tag tag-{{ $competence->categorie }}">{{ $competence->categorie }}</span>
                            <h3>{{ $competence->nom }}</h3>
                            @if($competence->niveau)
                                <p>{{ ucfirst($competence->niveau) }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($projects->isNotEmpty())
        <section id="projects" class="section">
            <div class="container">
                <p class="section-label">PROJECTS</p>
                <h2>Projets</h2>

                <div class="project-grid">
                    @foreach($projects->take(3) as $project)
                        @include('components.project-card', ['project' => $project])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($experiences->isNotEmpty())
        <section id="experience" class="section section-muted">
            <div class="container">
                <p class="section-label">EXPERIENCE</p>
                <h2>Expériences</h2>
                <div class="timeline">
                    @foreach($experiences as $experience)
                        <article class="timeline-item @if(!$experience->date_fin) timeline-item--current @endif">
                            <div class="timeline-date">
                                {{ $experience->date_debut->format('Y') }}
                                @if($experience->date_fin)
                                    - {{ $experience->date_fin->format('Y') }}
                                @else
                                    - Aujourd’hui
                                @endif
                            </div>
                            <div>
                                <h3>{{ $experience->titre }}</h3>
                                <p class="company">{{ $experience->entreprise }}</p>
                                @if($experience->lieu)
                                    <p>{{ $experience->lieu }}</p>
                                @endif
                                @if($experience->bilan)
                                    <ul class="bullet-list">
                                        @foreach($experience->bilan as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($formations->isNotEmpty())
        <section id="formation" class="section">
            <div class="container">
                <p class="section-label">EDUCATION</p>
                <h2>Formations</h2>
                <div class="timeline">
                    @foreach($formations as $formation)
                        <article class="timeline-item @if(!$formation->date_fin) timeline-item--current @endif">
                            <div class="timeline-date">
                                {{ $formation->date_debut->format('Y') }}
                                @if($formation->date_fin)
                                    - {{ $formation->date_fin->format('Y') }}
                                @else
                                    - En cours
                                @endif
                            </div>
                            <div>
                                <h3>{{ $formation->nom }}</h3>
                                <p class="company">{{ $formation->institut }}</p>
                                @if($formation->diplome)
                                    <p>{{ $formation->diplome }}</p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section id="contact" class="section section-muted">
        <div class="container narrow-container">
            <p class="section-label">CONTACT</p>
            <h2>Contact</h2>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('contact.store') }}" method="POST" class="contact-form">
                @csrf

                <div class="form-grid">
                    <div>
                        <label for="nom">Nom *</label>
                        <input id="nom" name="nom" type="text" value="{{ old('nom') }}" required>
                        @error('nom')<small class="error-message">{{ $message }}</small>@enderror
                    </div>

                    <div>
                        <label for="email">Email *</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                        @error('email')<small class="error-message">{{ $message }}</small>@enderror
                    </div>
                </div>

                <div>
                    <label for="sujet">Sujet *</label>
                    <input id="sujet" name="sujet" type="text" value="{{ old('sujet') }}" required>
                    @error('sujet')<small class="error-message">{{ $message }}</small>@enderror
                </div>

                <div>
                    <label for="contenu">Message *</label>
                    <textarea id="contenu" name="contenu" rows="6" required>{{ old('contenu') }}</textarea>
                    @error('contenu')<small class="error-message">{{ $message }}</small>@enderror
                </div>

                <button type="submit" class="btn btn-primary">Envoyer le message</button>
            </form>
        </div>
    </section>

    <div class="floating-actions" aria-label="Actions rapides">
        <a href="https://wa.me/22606913191" target="_blank" rel="noopener noreferrer" class="floating-action floating-action-whatsapp" aria-label="Contacter sur WhatsApp" title="Contacter sur WhatsApp">
            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path d="M20.5 3.5A11.8 11.8 0 0 0 12.1 0C5.6 0 .3 5.3.3 11.8c0 2.1.6 4.1 1.6 5.9L.2 24l6.5-1.7a11.8 11.8 0 0 0 5.4 1.3h.1c6.5 0 11.8-5.3 11.8-11.8 0-3.1-1.3-6.1-3.5-8.3Zm-8.4 18.1h-.1a9.8 9.8 0 0 1-5-1.4l-.4-.2-3.9 1 1-3.8-.3-.4a9.8 9.8 0 1 1 8.7 4.8Zm5.4-7.4c-.3-.2-1.8-.9-2.1-1-.3-.1-.5-.2-.7.2-.2.3-.8 1-1 1.2-.2.2-.4.2-.7.1-2-.9-3.3-1.7-4.6-3.8-.4-.6.4-.5 1.1-1.8.1-.2.1-.4 0-.6 0-.2-.7-1.6-.9-2.2-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.6.1-.9.4-.3.3-1.2 1.2-1.2 2.9s1.2 3.4 1.4 3.6c.2.2 2.4 3.7 5.9 5.2 2.2 1 2.2.7 2.6.7.4 0 1.8-.7 2-1.4.3-.7.3-1.3.2-1.4Z"/>
            </svg>
        </a>
        <button type="button" class="floating-action floating-action-top" aria-label="Remonter en haut" title="Remonter en haut">
            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="m6 14 6-6 6 6M12 8v10"/></svg>
        </button>
    </div>
@endsection

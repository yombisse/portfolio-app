@extends('layouts.app')

@section('title', 'Projets')

@section('content')
    <section class="section">
        <div class="container">
            <p class="section-label">PROJECTS</p>
            <h1>Projets</h1>

            @if($projects->isEmpty())
                <p>Aucun projet pour le moment.</p>
            @else
                <div class="project-grid">
                    @foreach($projects as $project)
                        @include('components.project-card', ['project' => $project])
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection

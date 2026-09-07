<?php

namespace App\Http\Controllers;

use App\Models\Competence;
use App\Models\Project;
use App\Services\CloudinaryMediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminProjectController extends Controller
{
    public function __construct(
        private CloudinaryMediaService $mediaService
    ) {}

    public function index(): View
    {
        $projects = Project::query()->with('competences')->orderByDesc('date_projet')->get();

        return view('admin.projects.index', compact('projects'));
    }

    public function create(): View
    {
        $project = new Project(['publie' => false]);
        $competences = Competence::query()->orderBy('nom')->get();

        return view('admin.projects.form', compact('project', 'competences'));
    }

    public function store(Request $request): RedirectResponse
    {
        $project = new Project;
        $this->saveProject($request, $project);

        return redirect()->route('admin.projects.index')->with('success', 'Le projet a été créé.');
    }

    public function edit(Project $project): View
    {
        $project->load('competences');
        $competences = Competence::query()->orderBy('nom')->get();

        return view('admin.projects.form', compact('project', 'competences'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $this->saveProject($request, $project);

        return redirect()->route('admin.projects.index')->with('success', 'Le projet a été mis à jour.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->deleteStoredImage($project->image_principale);
        $this->deleteStoredCaptures($project->captures ?? []);
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Le projet a été supprimé.');
    }

    private function saveProject(Request $request, Project $project): void
    {
        $validated = $request->validate([
            'titre' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string'],
            'role' => ['required', 'string', 'max:150'],
            'image_principale' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'github_url' => ['nullable', 'url', 'max:2048'],
            'url_projet' => ['nullable', 'url', 'max:2048'],
            'date_projet' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'publie' => ['nullable', 'boolean'],
            'fonctionnalites' => ['nullable', 'array', 'max:10'],
            'fonctionnalites.*' => ['required', 'string', 'max:150'],
            'captures' => ['nullable', 'array', 'max:10'],
            'captures.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'competences' => ['required', 'array', 'min:1', 'max:10'],
            'competences.*' => ['required', 'uuid', 'exists:competences,id'],
        ]);

        $fonctionnalites = array_values(array_filter(
            array_map('trim', $validated['fonctionnalites'] ?? []),
            static fn (string $value): bool => $value !== ''
        ));

        if (count($fonctionnalites) > 10) {
            throw ValidationException::withMessages([
                'fonctionnalites' => 'Vous pouvez ajouter au maximum 10 fonctionnalités.',
            ]);
        }

        $existingCaptures = $project->captures ?? [];
        $uploadedCaptures = $request->file('captures', []);

        if (count($existingCaptures) + count($uploadedCaptures) > 10) {
            throw ValidationException::withMessages([
                'captures' => 'Vous pouvez enregistrer au maximum 10 captures au total.',
            ]);
        }

        $captures = $existingCaptures;
        foreach ($uploadedCaptures as $capture) {
            $captures[] = $this->mediaService->upload($capture, 'projects/captures');
        }

        $isPublished = $request->boolean('publie');
        if ($isPublished && ! $request->hasFile('image_principale') && ! $project->image_principale) {
            throw ValidationException::withMessages([
                'image_principale' => 'Une image principale est obligatoire pour publier un projet.',
            ]);
        }

        if ($request->hasFile('image_principale')) {
            $this->deleteStoredImage($project->image_principale);
            $project->image_principale = $this->mediaService->upload($request->file('image_principale'), 'projects');
        }

        $project->fill([
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'role' => $validated['role'],
            'github_url' => $validated['github_url'] ?? null,
            'url_projet' => $validated['url_projet'] ?? null,
            'date_projet' => $validated['date_projet'] ?? null,
            'publie' => $isPublished,
            'fonctionnalites' => $fonctionnalites,
            'captures' => $captures,
        ]);
        DB::transaction(function () use ($project, $validated): void {
            $project->save();
            $project->competences()->sync($validated['competences'] ?? []);
        });
    }

    private function deleteStoredImage(?string $path): void
    {
        $this->mediaService->delete($path);
    }

    private function deleteStoredCaptures(array $captures): void
    {
        foreach ($captures as $capture) {
            $this->deleteStoredImage($capture);
        }
    }
}

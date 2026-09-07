<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Services\CloudinaryMediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminFormationController extends Controller
{
    public function __construct(
        private CloudinaryMediaService $mediaService
    ) {}

    public function index(): View
    {
        $formations = Formation::query()->orderByDesc('date_debut')->get();

        return view('admin.formations.index', compact('formations'));
    }

    public function create(): View
    {
        return view('admin.formations.form', ['formation' => new Formation]);
    }

    public function store(Request $request): RedirectResponse
    {
        $formation = new Formation;
        $this->saveFormation($request, $formation);

        return redirect()->route('admin.formations.index')->with('success', 'La formation a été créée.');
    }

    public function edit(Formation $formation): View
    {
        return view('admin.formations.form', compact('formation'));
    }

    public function update(Request $request, Formation $formation): RedirectResponse
    {
        $this->saveFormation($request, $formation);

        return redirect()->route('admin.formations.index')->with('success', 'La formation a été mise à jour.');
    }

    public function destroy(Formation $formation): RedirectResponse
    {
        $this->deleteFile($formation->justificatif);
        $formation->delete();

        return redirect()->route('admin.formations.index')->with('success', 'La formation a été supprimée.');
    }

    private function saveFormation(Request $request, Formation $formation): void
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:200'],
            'institut' => ['required', 'string', 'max:200'],
            'domaine' => ['nullable', 'string', 'max:150'],
            'diplome' => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'date_debut' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'date_fin' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:date_debut'],
            'justificatif' => ['nullable', 'file', 'mimes:pdf', 'max:8192'],
        ]);

        if ($request->hasFile('justificatif')) {
            $this->deleteFile($formation->justificatif);
            $validated['justificatif'] = $this->mediaService->upload($request->file('justificatif'), 'formations');
        }

        if (! $formation->getKey()) {
            $formation->setAttribute('id', (string) Str::uuid());
        }

        DB::transaction(function () use ($formation, $validated): void {
            $formation->fill([
                'nom' => $validated['nom'],
                'institut' => $validated['institut'],
                'domaine' => $validated['domaine'] ?? null,
                'diplome' => $validated['diplome'] ?? null,
                'description' => $validated['description'] ?? null,
                'date_debut' => $validated['date_debut'],
                'date_fin' => $validated['date_fin'] ?? null,
                'justificatif' => $validated['justificatif'] ?? $formation->justificatif,
            ]);
            $formation->save();
        });
    }

    private function deleteFile(?string $path): void
    {
        $this->mediaService->delete($path);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Services\CloudinaryMediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminExperienceController extends Controller
{
    public function __construct(
        private CloudinaryMediaService $mediaService
    ) {}

    public function index(): View
    {
        $experiences = Experience::query()->orderByDesc('date_debut')->get();

        return view('admin.experiences.index', compact('experiences'));
    }

    public function create(): View
    {
        return view('admin.experiences.form', ['experience' => new Experience]);
    }

    public function store(Request $request): RedirectResponse
    {
        $experience = new Experience;
        $this->saveExperience($request, $experience);

        return redirect()->route('admin.experiences.index')->with('success', 'L’expérience a été créée.');
    }

    public function edit(Experience $experience): View
    {
        return view('admin.experiences.form', compact('experience'));
    }

    public function update(Request $request, Experience $experience): RedirectResponse
    {
        $this->saveExperience($request, $experience);

        return redirect()->route('admin.experiences.index')->with('success', 'L’expérience a été mise à jour.');
    }

    public function destroy(Experience $experience): RedirectResponse
    {
        $this->deleteFile($experience->justificatif);
        $experience->delete();

        return redirect()->route('admin.experiences.index')->with('success', 'L’expérience a été supprimée.');
    }

    private function saveExperience(Request $request, Experience $experience): void
    {
        $validated = $request->validate([
            'titre' => ['required', 'string', 'max:200'],
            'entreprise' => ['required', 'string', 'max:200'],
            'lieu' => ['nullable', 'string', 'max:150'],
            'contact_entreprise' => ['nullable', 'email', 'max:255'],
            'date_debut' => ['required', 'date_format:Y-m-d'],
            'date_fin' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:date_debut', 'before_or_equal:today'],
            'bilan' => ['nullable', 'array', 'max:10'],
            'bilan.*' => ['required', 'string', 'max:250'],
            'justificatif' => ['nullable', 'file', 'mimes:pdf', 'max:8192'],
        ]);

        if ($validated['date_debut'] > now()->format('Y-m-d')) {
            throw ValidationException::withMessages([
                'date_debut' => 'La date de début ne peut pas être dans le futur.',
            ]);
        }

        $bilan = array_values(array_filter(
            array_map('trim', $validated['bilan'] ?? []),
            static fn (string $item): bool => $item !== ''
        ));

        if ($request->hasFile('justificatif')) {
            $this->deleteFile($experience->justificatif);
            $validated['justificatif'] = $this->mediaService->upload($request->file('justificatif'), 'experiences');
        }

        if (! $experience->getKey()) {
            $experience->setAttribute('id', (string) Str::uuid());
        }

        DB::transaction(function () use ($experience, $validated, $bilan): void {
            $experience->fill([
                'titre' => $validated['titre'],
                'entreprise' => $validated['entreprise'],
                'lieu' => $validated['lieu'] ?? null,
                'contact_entreprise' => $validated['contact_entreprise'] ?? null,
                'date_debut' => $validated['date_debut'],
                'date_fin' => $validated['date_fin'] ?? null,
                'bilan' => $bilan,
                'justificatif' => $validated['justificatif'] ?? $experience->justificatif,
            ]);
            $experience->save();
        });
    }

    private function deleteFile(?string $path): void
    {
        $this->mediaService->delete($path);
    }
}

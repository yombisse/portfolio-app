<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Services\CloudinaryMediaService;
use App\Services\PortfolioService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminProfileController extends Controller
{
    public function __construct(
        protected PortfolioService $portfolioService,
        private CloudinaryMediaService $mediaService
    ) {}

    public function edit(): View
    {
        $storedProfile = Profile::query()->first();
        $profile = $storedProfile ?: $this->portfolioService->getProfile();

        return view('admin.profile', compact('profile', 'storedProfile'));
    }

    public function store(Request $request): RedirectResponse
    {
        return $this->saveProfile($request);
    }

    public function update(Request $request): RedirectResponse
    {
        return $this->saveProfile($request);
    }

    public function destroy(): RedirectResponse
    {
        $profile = Profile::query()->first();

        if ($profile) {
            $this->deleteStoredFile($profile->photo);
            $this->deleteStoredFile($profile->cv);
            $profile->delete();
        }

        return redirect()
            ->route('admin.profile')
            ->with('success', 'Le profil enregistré a été supprimé. Le site public utilise maintenant les données de démonstration.');
    }

    private function saveProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:150'],
            'titre' => ['required', 'string', 'max:200'],
            'bio' => ['required', 'string'],
            'focus' => ['nullable', 'string'],
            'work_style' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'localisation' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'cv' => ['nullable', 'file', 'mimes:pdf', 'max:8192'],
            'reseaux' => ['nullable', 'array', 'max:10'],
            'reseaux.*.nom' => ['required', 'string', 'max:50'],
            'reseaux.*.url' => ['required', 'url', 'max:2048'],
        ]);

        $profile = Profile::query()->first() ?: new Profile;

        if ($request->hasFile('photo')) {
            $this->deleteStoredFile($profile->photo);
            $validated['photo'] = $this->mediaService->upload($request->file('photo'), 'profiles');
        }

        if ($request->hasFile('cv')) {
            $this->deleteStoredFile($profile->cv);
            $validated['cv'] = $this->mediaService->upload($request->file('cv'), 'profiles');
        }

        $reseauxSociaux = [];
        foreach ($validated['reseaux'] ?? [] as $reseau) {
            $nom = trim($reseau['nom']);
            $key = str($nom)->slug('_')->toString();

            if ($key !== '') {
                $reseauxSociaux[$key] = [
                    'nom' => $nom,
                    'url' => $reseau['url'],
                ];
            }
        }

        $profile->fill([
            'nom' => $validated['nom'],
            'titre' => $validated['titre'],
            'bio' => $validated['bio'],
            'focus' => $validated['focus'] ?? null,
            'work_style' => $validated['work_style'] ?? null,
            'photo' => $validated['photo'] ?? $profile->photo,
            'localisation' => $validated['localisation'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            'cv' => $validated['cv'] ?? $profile->cv,
            'reseaux_sociaux' => $reseauxSociaux,
        ]);
        $profile->save();

        return redirect()
            ->route('admin.profile')
            ->with('success', 'Le profil a été enregistré avec succès.');
    }

    private function deleteStoredFile(?string $path): void
    {
        $this->mediaService->delete($path);
    }
}

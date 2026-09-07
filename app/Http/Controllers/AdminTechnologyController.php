<?php

namespace App\Http\Controllers;

use App\Models\Competence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTechnologyController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->validate([
            'categorie' => ['nullable', 'in:technologie,aptitude'],
        ])['categorie'] ?? null;

        $competences = Competence::query()
            ->when($category, fn ($query) => $query->where('categorie', $category))
            ->withCount('projects')
            ->orderBy('categorie')
            ->orderBy('nom')
            ->get();

        return view('admin.technologies.index', compact('competences', 'category'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateCompetence($request);

        Competence::create($validated);

        return redirect()->route('admin.technologies.index')->with('success', 'La compétence a été ajoutée.');
    }

    public function update(Request $request, Competence $competence): RedirectResponse
    {
        $competence->update($this->validateCompetence($request));

        return redirect()->route('admin.technologies.index')->with('success', 'La compétence a été mise à jour.');
    }

    public function destroy(Competence $competence): RedirectResponse
    {
        $competence->delete();

        return redirect()->route('admin.technologies.index')->with('success', 'La compétence a été supprimée.');
    }

    private function validateCompetence(Request $request): array
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'categorie' => ['required', 'in:technologie,aptitude'],
            'niveau' => ['nullable', 'required_if:categorie,technologie', 'in:débutant,intermédiaire,avancé,expert'],
        ]);

        if ($validated['categorie'] === 'aptitude') {
            $validated['niveau'] = null;
        }

        return $validated;
    }
}

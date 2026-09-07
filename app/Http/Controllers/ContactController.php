<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return redirect()->to(route('home').'#contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:255'],
            'sujet' => ['required', 'string', 'max:200'],
            'contenu' => ['required', 'string'],
        ]);

        Message::create([
            'nom' => $validated['nom'],
            'email' => $validated['email'],
            'sujet' => $validated['sujet'],
            'contenu' => $validated['contenu'],
            'lu' => false,
        ]);

        return redirect()->to(route('home').'#contact')->with('success', 'Votre message a bien été envoyé.');
    }
}

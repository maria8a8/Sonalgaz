<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Poste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PosteController extends Controller
{
    public function index(Request $request)
    {
        return Poste::query()
            ->when($request->categorie, function($query, $categorie) {
                return $query->where('categorie', $categorie);
            })
            ->when($request->search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('code_poste', 'like', "%{$search}%")
                      ->orWhere('localisation', 'like', "%{$search}%")
                      ->orWhere('entreprise', 'like', "%{$search}%")
                      ->orWhere('mots_cles', 'like', "%{$search}%")
                      ->orWhere('numero_boite', 'like', "%{$search}%")
                      ->orWhere('rayonnage', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'categorie' => ['required', Rule::in(['genie_civil', 'isometrique', 'soudure', 'tuyauterie', 'protection'])],
            'code_poste' => 'required|string',
            'localisation' => 'required|string',
            'date_realisation' => 'required|date',
            'entreprise' => 'required|string',
            'mots_cles' => 'required|string',
            'observations' => 'nullable|string',
            'numero_boite' => 'nullable|string|max:255',
            'rayonnage' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('postes', 'public');
            $validated['file_path'] = $path;
        }

        $poste = Poste::create($validated);

        return response()->json($poste, 201);
    }

    public function show(Poste $poste)
    {
        return response()->json($poste);
    }

    public function update(Request $request, Poste $poste)
    {
        $validated = $request->validate([
            'categorie' => ['sometimes', Rule::in(['genie_civil', 'isometrique', 'soudure', 'tuyauterie', 'protection'])],
            'code_poste' => 'sometimes|string',
            'localisation' => 'sometimes|string',
            'date_realisation' => 'sometimes|date',
            'entreprise' => 'sometimes|string',
            'mots_cles' => 'sometimes|string',
            'observations' => 'nullable|string',
            'numero_boite' => 'nullable|string|max:255',
            'rayonnage' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('file')) {
            if ($poste->file_path) {
                Storage::disk('public')->delete($poste->file_path);
            }
            $path = $request->file('file')->store('postes', 'public');
            $validated['file_path'] = $path;
        }

        $poste->update($validated);

        return response()->json($poste);
    }

    public function destroy(Poste $poste)
    {
        if ($poste->file_path) {
            Storage::disk('public')->delete($poste->file_path);
        }
        $poste->delete();

        return response()->json(null, 204);
    }
}

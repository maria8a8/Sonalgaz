<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ligne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LigneController extends Controller
{
    public function index(Request $request)
    {
        return Ligne::query()
            ->when($request->plan_type, function($query, $plan_type) {
                return $query->where('plan_type', $plan_type);
            })
            ->when($request->search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('nom_ligne', 'like', "%{$search}%")
                      ->orWhere('region', 'like', "%{$search}%")
                      ->orWhere('numero_planche', 'like', "%{$search}%")
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
            'plan_type' => ['required', Rule::in(['vue_plan', 'profil_long', 'point_singulier', 'carte_generale', 'schema_equipement', 'terrain'])],
            'region' => 'required|string',
            'type_reseau' => 'nullable|string',
            'numero_planche' => 'required|string',
            'nom_ligne' => [
                'required',
                'string',
                Rule::unique('lignes')->where(function ($query) use ($request) {
                    return $query->where('district', $request->district);
                })
            ],
            'district' => 'required|string',
            'distance_gc' => 'required|numeric',
            'echelle' => 'required|string',
            'date_creation' => 'required|date',
            'entreprise_realisatrice' => 'required|string',
            'numero_contrat' => 'nullable|string',
            'mots_cles' => 'required|string',
            'observations' => 'nullable|string',
            'numero_boite' => 'nullable|string|max:255',
            'rayonnage' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('lignes', 'public');
            $validated['file_path'] = $path;
        }

        $ligne = Ligne::create($validated);

        return response()->json($ligne, 201);
    }

    public function show(Ligne $ligne)
    {
        return response()->json($ligne);
    }

    public function update(Request $request, Ligne $ligne)
    {
        $validated = $request->validate([
            'plan_type' => ['sometimes', Rule::in(['vue_plan', 'profil_long', 'point_singulier', 'carte_generale', 'schema_equipement', 'terrain'])],
            'region' => 'sometimes|string',
            'type_reseau' => 'sometimes|string',
            'numero_planche' => 'sometimes|string',
            'nom_ligne' => 'sometimes|string',
            'district' => 'sometimes|string',
            'distance_gc' => 'sometimes|numeric',
            'echelle' => 'sometimes|string',
            'date_creation' => 'sometimes|date',
            'entreprise_realisatrice' => 'sometimes|string',
            'numero_contrat' => 'sometimes|string',
            'mots_cles' => 'sometimes|string',
            'observations' => 'nullable|string',
            'numero_boite' => 'nullable|string|max:255',
            'rayonnage' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('file')) {
            if ($ligne->file_path) {
                Storage::disk('public')->delete($ligne->file_path);
            }
            $path = $request->file('file')->store('lignes', 'public');
            $validated['file_path'] = $path;
        }

        $ligne->update($validated);

        return response()->json($ligne);
    }

    public function destroy(Ligne $ligne)
    {
        if ($ligne->file_path) {
            Storage::disk('public')->delete($ligne->file_path);
        }
        $ligne->delete();

        return response()->json(null, 204);
    }
}

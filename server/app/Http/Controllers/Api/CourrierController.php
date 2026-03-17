<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Courrier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CourrierController extends Controller
{
    public function index(Request $request)
    {
        return Courrier::query()
            ->when($request->type, function($query, $type) {
                return $query->where('type', $type);
            })
            ->when($request->search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('objet', 'like', "%{$search}%")
                      ->orWhere('expediteur', 'like', "%{$search}%")
                      ->orWhere('destinataire', 'like', "%{$search}%")
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
            'type' => ['required', Rule::in(['arrive', 'depart'])],
            'date_reception' => 'required|date',
            'expediteur' => 'required|string|max:255',
            'destinataire' => 'required|string|max:255',
            'objet' => 'required|string|max:255',
            'description' => 'nullable|string',
            'numero_boite' => 'nullable|string|max:255',
            'rayonnage' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('courriers', 'public');
            $validated['file_path'] = $path;
        }

        $courrier = Courrier::create($validated);

        return response()->json($courrier, 201);
    }

    public function show(Courrier $courrier)
    {
        return response()->json($courrier);
    }

    public function update(Request $request, Courrier $courrier)
    {
        $validated = $request->validate([
            'type' => ['sometimes', Rule::in(['arrive', 'depart'])],
            'date_reception' => 'sometimes|date',
            'expediteur' => 'sometimes|string|max:255',
            'destinataire' => 'sometimes|string|max:255',
            'objet' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'numero_boite' => 'nullable|string|max:255',
            'rayonnage' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($courrier->file_path) {
                Storage::disk('public')->delete($courrier->file_path);
            }
            $path = $request->file('file')->store('courriers', 'public');
            $validated['file_path'] = $path;
        }

        $courrier->update($validated);

        return response()->json($courrier);
    }

    public function destroy(Courrier $courrier)
    {
        if ($courrier->file_path) {
            Storage::disk('public')->delete($courrier->file_path);
        }
        $courrier->delete();

        return response()->json(null, 204);
    }
}

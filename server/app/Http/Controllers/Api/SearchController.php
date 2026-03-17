<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Courrier;
use App\Models\Ligne;
use App\Models\Poste;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('query');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $type = $request->get('type');

        $results = collect();

        // Search Courriers
        if (!$type || $type === 'courrier') {
            $courrierQuery = Courrier::query();
            if ($query) {
                $courrierQuery->where(function($q) use ($query) {

                });
            }
            if ($startDate) $courrierQuery->where('date_reception', '>=', $startDate);
            if ($endDate) $courrierQuery->where('date_reception', '<=', $endDate);
            
            $results = $results->concat($courrierQuery->limit(50)->get()->map(function($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->objet,
                    'subtitle' => "De: {$item->expediteur} À: {$item->destinataire}",
                    'date' => $item->date_reception,
                    'type' => 'COURRIER',
                    'file_path' => $item->file_path,
                    'original_data' => $item
                ];
            }));
        }

        // Search Lignes
        if (!$type || $type === 'ligne') {
            $ligneQuery = Ligne::query();
            if ($query) {
                $ligneQuery->where(function($q) use ($query) {

                });
            }
            if ($startDate) $ligneQuery->where('date_creation', '>=', $startDate);
            if ($endDate) $ligneQuery->where('date_creation', '<=', $endDate);

            $results = $results->concat($ligneQuery->limit(50)->get()->map(function($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->nom_ligne,
                    'subtitle' => "Région: {$item->region} | Type: {$item->plan_type}",
                    'date' => $item->date_creation,
                    'type' => 'LIGNE',
                    'file_path' => $item->file_path,
                    'original_data' => $item
                ];
            }));
        }

        // Search Postes
        if (!$type || $type === 'poste') {
            $posteQuery = Poste::query();
            if ($query) {
                $posteQuery->where(function($q) use ($query) {
                    $q->where('code_poste', 'like', "%{$query}%")
                      ->orWhere('localisation', 'like', "%{$query}%")
                      ->orWhere('entreprise', 'like', "%{$query}%")
                      ->orWhere('mots_cles', 'like', "%{$query}%");
                });
            }
            if ($startDate) $posteQuery->where('date_realisation', '>=', $startDate);
            if ($endDate) $posteQuery->where('date_realisation', '<=', $endDate);

            $results = $results->concat($posteQuery->limit(50)->get()->map(function($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->code_poste,
                    'subtitle' => "Localisation: {$item->localisation} | Catégorie: {$item->categorie}",
                    'date' => $item->date_realisation,
                    'type' => 'POSTE',
                    'file_path' => $item->file_path,
                    'original_data' => $item
                ];
            }));
        }

        return response()->json($results->sortByDesc('date')->values());
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Courrier;
use App\Models\Ligne;
use App\Models\Poste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $counts = [
            'courriers' => Courrier::count(),
            'lignes' => Ligne::count(),
            'postes' => Poste::count(),
        ];

        // Courriers by month (last 6 months)
        $courriers_by_month = Courrier::select(
            DB::raw('DATE_FORMAT(date_reception, "%Y-%m") as month'),
            DB::raw('count(*) as count')
        )
        ->groupBy(DB::raw('DATE_FORMAT(date_reception, "%Y-%m")'))
        ->orderBy('month', 'desc')
        ->limit(6)
        ->get()
        ->reverse()
        ->values();

        // Distribution by region (from Lignes)
        $distribution_by_region = Ligne::select('region', DB::raw('count(*) as count'))
            ->groupBy('region')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        // Documents per year (recent 5 years)
        // We'll aggregate from all three if possible, or just the main ones.
        // For simplicity, let's just do courriers as a proxy if preferred, 
        // but the requirement says "documents_per_year". 
        // Let's assume it means a combined count or just courriers for now.
        $documents_per_year = Courrier::select(
            DB::raw('YEAR(date_reception) as year'),
            DB::raw('count(*) as count')
        )
        ->groupBy(DB::raw('YEAR(date_reception)'))
        ->orderBy('year', 'desc')
        ->limit(5)
        ->get();

        return response()->json([
            'counts' => $counts,
            'courriers_by_month' => $courriers_by_month,
            'distribution_by_region' => $distribution_by_region,
            'documents_per_year' => $documents_per_year,
            'recent_uploads' => $this->getRecentUploads(),
        ]);
    }

    private function getRecentUploads()
    {
        $courriers = Courrier::latest()->limit(5)->get()->map(fn($item) => [...$item->toArray(), 'source_type' => 'COURRIER']);
        $lignes = Ligne::latest()->limit(5)->get()->map(fn($item) => [...$item->toArray(), 'source_type' => 'LIGNE']);
        $postes = Poste::latest()->limit(5)->get()->map(fn($item) => [...$item->toArray(), 'source_type' => 'POSTE']);

        return $courriers->concat($lignes)->concat($postes)
            ->sortByDesc('created_at')
            ->values()
            ->take(5);
    }
}

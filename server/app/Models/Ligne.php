<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ligne extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_type',
        'region',
        'type_reseau',
        'numero_planche',
        'nom_ligne',
        'district',
        'distance_gc',
        'echelle',
        'date_creation',
        'entreprise_realisatrice',
        'numero_contrat',
        'mots_cles',
        'observations',
        'numero_boite',
        'rayonnage',
        'file_path',
    ];
}

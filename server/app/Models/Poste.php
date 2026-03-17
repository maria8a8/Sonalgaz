<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poste extends Model
{
    use HasFactory;

    protected $fillable = [
        'categorie',
        'code_poste',
        'localisation',
        'date_realisation',
        'entreprise',
        'mots_cles',
        'observations',
        'numero_boite',
        'rayonnage',
        'file_path',
    ];
}

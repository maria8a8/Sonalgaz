<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courrier extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'date_reception',
        'expediteur',
        'destinataire',
        'objet',
        'description',
        'numero_boite',
        'rayonnage',
        'file_path',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Programmation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'num_salle',
        'code_ec',
        'code_personnel',
        'nbre_heures',
        'date',
        'heure_debut',
        'heure_fin',
        'status'];

    public function salle(): BelongsTo
    {
        return $this->belongsTo(Salle::class, 'num_salle', 'num_salle');
    }

    public function ec(): BelongsTo
    {
        return $this->belongsTo(Ec::class, 'code_ec', 'code_ec');
    }

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'code_personnel', 'code_personnel');
    }
}

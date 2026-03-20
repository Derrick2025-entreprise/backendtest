<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class EC extends Model
{
    use HasFactory;

    protected $primaryKey = 'code_ec';
    protected $table='ec';
    public $timestamps = true;

    protected $casts = [
        'nb_heures_ec' => 'int',
        'nb_credits_ec' => 'int'
    ];

    protected $fillable = [
        'code_ec',
        'label_ec',
        'description_ec',
        'nb_heures_ec',
        'nb_credits_ec',
        'code_ue',
        'support_cours',
        'support_cours_url'
    ];

    public function ue(): BelongsTo {
        return $this->belongsTo(UE::class, 'code_ue', 'code_ue');
    }

    public function personnels(): BelongsToMany {
        return $this->belongsToMany(Personnel::class, 'enseignements', 'code_ec', 'code_personnel');
    }

    public function programmations(): HasMany
    {
        return $this->hasMany(Programmation::class, 'code_ec', 'code_ec');
    }
}

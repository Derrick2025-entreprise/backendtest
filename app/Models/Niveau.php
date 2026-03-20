<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Niveau extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'code_niveau';
    protected $table = 'niveau';

    protected $fillable = ['label_niveau', 'description_niveau', 'code_filiere'];

    public function filiere(): BelongsTo {
        return $this->belongsTo(Filiere::class, 'code_filiere', 'code_filiere');
    }

    public function ue(): HasMany {
        return $this->hasMany(UE::class, 'code_niveau', 'code_niveau');
    }
}

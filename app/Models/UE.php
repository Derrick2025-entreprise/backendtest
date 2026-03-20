<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UE extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'code_ue';
    protected $table = 'ue';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['code_ue', 'label_ue', 'description_ue', 'code_niveau'];

    public function niveau(): BelongsTo {
        return $this->belongsTo(Niveau::class, 'code_niveau', 'code_niveau');
    }

    public function ec(): HasMany {
        return $this->hasMany(EC::class, 'code_ue', 'code_ue');
    }
}

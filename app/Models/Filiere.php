<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Filiere extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'code_filiere';
    protected $table='filiere';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['code_filiere', 'label_filiere', 'description_filiere'];

    public function niveaux(): HasMany {
        return $this->hasMany(Niveau::class, 'code_filiere', 'code_filiere');
    }
}

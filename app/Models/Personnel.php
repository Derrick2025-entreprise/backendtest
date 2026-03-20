<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Personnel extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;
    
    protected $primaryKey = 'code_personnel';
    protected $table = 'personnel';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'code_personnel',
        'nom_personnel',
        'prenom_personnel',
        'sexe_personnel',
        'phone_personnel',
        'login_personnel',
        'password_personnel',
        'type_personnel'
    ];

     protected $hidden = [
        "password_personnel"
    ];

    public function ecs(): BelongsToMany {
        return $this->belongsToMany(EC::class, 'enseignements', 'code_personnel', 'code_ec');
    }

    public function salles(): HasMany {
        return $this->hasMany(Programmation::class, 'code_personnel', 'code_personnel');
    }
}

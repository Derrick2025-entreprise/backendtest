<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salle extends Model
{
    use HasFactory;

    protected $primaryKey = 'num_salle';
    public $incrementing = false;

    protected $fillable = ['num_salle', 'contenance', 'status'];

    public function programmations(): HasMany
    {
        return $this->hasMany(Programmation::class, 'num_salle', 'num_salle');
    }
}

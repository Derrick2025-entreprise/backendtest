<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enseignement extends Model
{
    use HasFactory;

    protected $fillable = ['code_personnel', 'code_ec'];

    public function ec(): BelongsTo
    {
        return $this->belongsTo(EC::class, 'code_ec', 'code_ec');
    }

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'code_personnel', 'code_personnel');
    }
}

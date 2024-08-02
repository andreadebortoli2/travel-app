<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Trip;
use App\Models\Stop;

class day extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'title', 'description', 'trip_id'];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function stops(): HasMany
    {
        return $this->hasMany(Stop::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Day;

class trip extends Model
{
    use HasFactory;

    public function days(): HasMany
    {
        return $this->hasMany(Day::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Day;

class stop extends Model
{
    use HasFactory;

    public function day(): BelongsTo
    {
        return $this->belongsTo(Day::class);
    }
}

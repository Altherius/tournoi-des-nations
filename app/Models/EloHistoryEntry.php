<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloHistoryEntry extends Model
{
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function opposing_team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'opposing_team_id');
    }

    use HasFactory;
}

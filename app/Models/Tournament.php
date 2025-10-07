<?php

namespace App\Models;

use Abbasudo\Purity\Traits\Filterable;
use Abbasudo\Purity\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tournament extends Model
{
    use HasFactory, Filterable, Sortable;

    public function goldTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'gold_team_id');
    }

    public function silverTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'silver_team_id');
    }

    public function bronzeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'bronze_team_id');
    }
}

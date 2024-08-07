<?php

namespace App\Models;

use App\Events\GameCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Game extends Model
{
    use HasFactory;

    public function hostingTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'hosting_team_id');
    }

    public function receivingTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'receiving_team_id');
    }

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    protected $dispatchesEvents = [
        'created' => GameCreated::class,
    ];
}

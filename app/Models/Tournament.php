<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int        $id
 * @property string     $name
 * @property DateTime   $starts_at
 * @property DateTime   $ends_at
 * @property int        $gold_team_id
 * @property int        $silver_team_id
 * @property int        $bronze_team_id
 */
class Tournament extends Model
{
    use HasFactory;

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

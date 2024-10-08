<?php

namespace App\Models;

use App\Enum\Region;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    public function gamesHosting(): HasMany
    {
        return $this->hasMany(Game::class, 'hosting_team_id');
    }

    public function gamesReceiving(): HasMany
    {
        return $this->hasMany(Game::class, 'receiving_team_id');
    }

    protected $casts = [
        'region' => Region::class,
    ];
}

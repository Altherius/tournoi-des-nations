<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@tournoi-des-nations.ovh',
        ]);

        $teams = Team::factory(10)->create();
        $tournaments = Tournament::factory(5)->recycle($teams)->create();

        Game::factory(20)->recycle($teams)->recycle($tournaments)->create();
    }
}

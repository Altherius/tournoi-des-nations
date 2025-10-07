<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Console\Command;

class ImportGames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-games';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import games from a CSV file';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $filename = storage_path('/app/matches.csv');
        $file = fopen($filename, 'r');
        while (($data = fgetcsv($file, 200)) !== false) {

            $tournament = Tournament::where('name', $data[0])->first();
            $hostingTeam = Team::where('name', $data[1])->first();
            $receivingTeam = Team::where('name', $data[2])->first();

            if (!$tournament) {
                $this->error('Tournament not found : ' . $data[0]);
                continue;
            }
            if (!$hostingTeam) {
                $this->error('Hosting team not found : ' . $data[1]);
                continue;
            }
            if (!$receivingTeam) {
                $this->error('Receiving team not found : ' . $data[2]);
                continue;
            }

            $game = new Game();

            $game->hosting_team_id = $hostingTeam->id;
            $game->receiving_team_id = $receivingTeam->id;
            $game->tournament_id = $tournament->id;
            $game->host_score_1 = (int) $data[3];
            $game->guest_score_1 = (int) $data[4];
            $game->host_score_2 = (int) $data[5];
            $game->guest_score_2 = (int) $data[6];

            $game->save();

            $this->info("{$game->tournament->name} : {$game->hostingTeam->name} - {$game->receivingTeam->name} saved");
        }
    }
}

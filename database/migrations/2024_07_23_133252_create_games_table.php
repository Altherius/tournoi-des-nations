<?php

use App\Models\Team;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class, 'hosting_team_id');
            $table->foreignIdFor(Team::class, 'receiving_team_id');
            $table->foreignIdFor(Team::class, 'winning_team_id')->nullable();
            $table->integer('host_score_1');
            $table->integer('guest_score_1');
            $table->integer('host_score_2');
            $table->integer('guest_score_2');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};

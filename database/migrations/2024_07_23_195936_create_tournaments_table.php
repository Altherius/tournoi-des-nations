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
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('elo_multiplier')->default(1.0);
            $table->boolean('major')->default(true);
            $table->boolean('balancing')->default(false);
            $table->foreignIdFor(Team::class, 'gold_team_id')->nullable();
            $table->foreignIdFor(Team::class, 'silver_team_id')->nullable();
            $table->foreignIdFor(Team::class, 'bronze_team_id')->nullable();
            $table->date('starts_at');
            $table->date('ends_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};

<?php

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
        Schema::disableForeignKeyConstraints();

        Schema::create('card_season', function (Blueprint $table) {
            $table->bigInteger('card_id')->unique();
            $table->foreign('card_id')->references('id')->on('cards');
            $table->bigInteger('season_id')->unique();
            $table->foreign('season_id')->references('id')->on('seasons');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_season');
    }
};

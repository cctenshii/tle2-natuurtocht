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

        Schema::create('card_location', function (Blueprint $table) {
            $table->bigInteger('card_id')->unique();
            $table->foreign('card_id')->references('id')->on('cards');
            $table->bigInteger('location_id')->unique();
            $table->foreign('location_id')->references('id')->on('locations');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_location');
    }
};

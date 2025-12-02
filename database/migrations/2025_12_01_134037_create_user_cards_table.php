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

        Schema::create('user_cards', function (Blueprint $table) {
            $table->bigInteger('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('card_id')->unique();
            $table->foreign('card_id')->references('id')->on('cards');
            $table->date('acquired_at');
            $table->string('image_url', 255);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_cards');
    }
};

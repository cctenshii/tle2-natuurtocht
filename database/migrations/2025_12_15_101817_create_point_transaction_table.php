<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // card_id is OPTIONAL (bv friend_added heeft geen kaart)
            $table->foreignId('card_id')
                ->nullable()
                ->constrained('cards')
                ->nullOnDelete();

            // bv: card_collected, card_shiny, friend_added
            $table->string('action', 50);

            // +15, +30, +5 etc.
            $table->integer('points');

            // extra context
            $table->json('meta')->nullable();

            $table->timestamps();

            // optioneel: snel filteren
            $table->index(['user_id', 'action']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
    }
};

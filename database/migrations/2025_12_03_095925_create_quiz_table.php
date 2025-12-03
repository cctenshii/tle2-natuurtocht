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

        Schema::create('quiz', function (Blueprint $table) {
            $table->id();
            $table->json('answers');
            $table->text('question_text');
            $table->text('explanation')->nullable();
            $table->bigInteger('card_id');
            $table->foreign('card_id')->references('id')->on('cards');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz');
    }
};

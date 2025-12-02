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

        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->bigInteger('number');
            $table->text('properties');
            $table->text('description');
            $table->boolean('shiny');
            $table->text('question');
            $table->text('answer');
            $table->bigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};

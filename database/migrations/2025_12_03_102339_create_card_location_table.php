<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('card_location', function (Blueprint $table) {
            $table->foreignId('card_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();

            $table->unique(['card_id', 'location_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('card_location');
    }
};

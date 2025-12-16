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
        Schema::table('user_cards', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->timestamp('quiz_completed_at')->nullable();
            $table->boolean('quiz_correct')->nullable();
            $table->string('quiz_answer_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('user_cards', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn(['quiz_completed_at', 'quiz_correct', 'quiz_answer_id']);
        });
    }

};

<?php
// ..._create_fauna_flora_items_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nature_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('number')->unique(); // e.g., "001"
            $table->string('name'); // e.g., "Eik"
            $table->string('sub_group'); // e.g., "Bomenrijk", "Struikenrijk"
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nature_items');
    }
};

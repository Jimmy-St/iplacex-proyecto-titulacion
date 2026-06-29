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
        Schema::create('total_day', function (Blueprint $table) {
            $table->id(); // Tu ID autoincremental (Primary Key)

            $table->integer('total_items')->default(0);
            $table->decimal('total_amount', 12, 2)->default(0.00);

            // Campo de fecha con restricción de unicidad para el upsert
            $table->date('date')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('total_day');
    }
};

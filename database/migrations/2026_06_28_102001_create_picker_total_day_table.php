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
        Schema::create('picker_total_day', function (Blueprint $table) {
            $table->id(); // Tu ID autoincremental (Primary Key)

            // Llave foránea conceptual hacia pickers
            $table->unsignedBigInteger('picker_id');

            $table->date('date');

            // Métricas de control y auditoría
            $table->integer('total_tasks')->default(0);
            $table->integer('total_items')->default(0);
            $table->decimal('total_amount', 12, 2)->default(0.00);

            // Resultado final de la fórmula
            $table->decimal('total_points', 12, 2)->default(0.00);

            // ¡EL CANDADO DE IDEMPOTENCIA! 
            // Índice único compuesto: evita que un picker tenga más de un registro por día
            $table->unique(['picker_id', 'date'], 'picker_total_day_picker_date_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('picker_total_day');
    }
};

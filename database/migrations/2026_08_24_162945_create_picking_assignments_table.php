<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('picking_assignments', function (Blueprint $table) {
            $table->id();

            // Llaves foráneas hacia tasks y pickers
            $table->foreignId('picking_task_id')
                ->constrained('picking_tasks')
                ->onDelete('cascade');

            $table->foreignId('picker_id')
                ->constrained('pickers')
                ->onDelete('cascade');

            $table->timestamps();

            // Restricción única para evitar que un picker sea asignado dos veces a la misma tarea
            $table->unique(['picking_task_id', 'picker_id'], 'task_picker_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('picking_assignments');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 100)->unique();

            // seller_id es nullable e unsigned bigint
            $table->unsignedBigInteger('seller_id')->nullable()->default(null);

            // Nombre del seller en texto plano (opcional por ahora)
            $table->string('seller', 100)->nullable();

            $table->decimal('total_amount', 12, 2);
            $table->string('status', 40)->nullable();

            // Manejo de tiempos nativos automatizados
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};

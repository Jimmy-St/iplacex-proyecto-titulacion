<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_items', function (Blueprint $table) {
            $table->id();

            // Relación con la tabla padre (tickets)
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');

            $table->string('product_code', 100);
            $table->string('product_name', 255);
            $table->integer('quantity');
            $table->decimal('price', 12, 2);
            $table->decimal('subtotal', 12, 2);

            $table->timestamps(); // create_at y updated_at nativos
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_items');
    }
};

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
        // 1. Creamos SELLERS primero (no depende de nadie)
        Schema::create('sellers', function (Blueprint $table) {
            $table->id(); // BIGINT PRIMARY KEY AUTOINCREMENT
            $table->string('employee_code', 50)->unique(); // Código único de empleado
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->boolean('is_active')->default(true); // TINYINT(1) equivalente
            $table->timestamps(); // created_at y updated_at
        });

        // 2. Creamos TICKETS (depende de sellers)
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 100)->unique();

            // Llave foránea hacia sellers. 
            // Si quieres que un ticket pueda NO tener vendedor, agrega ->nullable() antes de ->constrained()
            $table->foreignId('seller_id')->constrained('sellers')->onDelete('restrict');

            $table->decimal('total_amount', 12, 2);
            $table->timestamp('issued_at'); // Fecha de emisión del ticket
            $table->timestamps();
        });

        // 3. Creamos TICKET_ITEMS (depende de tickets)
        Schema::create('ticket_items', function (Blueprint $table) {
            $table->id();

            // Llave foránea hacia tickets. Si se borra el ticket, se borran sus ítems en cascada.
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');

            $table->string('product_code', 100);
            $table->string('product_name', 255);
            $table->integer('quantity');
            $table->decimal('price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Al deshacer, se borran en orden inverso para no romper las restricciones de llaves foráneas
        Schema::dropIfExists('ticket_items');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('sellers');
    }
};

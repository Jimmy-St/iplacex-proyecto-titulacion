<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Schema::table (no create): modifica la tabla existente sin borrar datos.
        Schema::table('tickets', function (Blueprint $table) {
            // Comentario/glosa libre del pedido. Puede venir vacío desde el JS.
            $table->string('comment', 255)->nullable()->after('seller');

            // Nombre del cliente tal como aparece en BICOM (texto libre, no relación).
            $table->string('customer', 150)->nullable()->after('comment');

            // Tipo de pago (ej: "CRÉDITO", "CONTADO", etc.), también texto libre por ahora.
            $table->string('payment_type', 100)->nullable()->after('customer');
        });
    }

    public function down(): void
    {
        // Reversible: si se hace rollback, se sacan solo las columnas agregadas acá.
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['comment', 'customer', 'payment_type']);
        });
    }
};

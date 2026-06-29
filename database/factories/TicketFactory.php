<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\TicketItem; // Asegúrate de importar el modelo de tus ítems
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    public function definition(): array
    {
        $fechaSimulada = $this->faker->dateTimeBetween('-1 year', 'now');

        return [
            'ticket_number' => 'TKT-' . $this->faker->unique()->numberBetween(100000, 999999),
            'seller_id'     => $this->faker->optional(0.7)->numberBetween(1, 10),
            'seller'        => $this->faker->name(),
            'total_amount'  => $this->faker->randomFloat(2, 1000, 50000),
            'status'        => $this->faker->randomElement(['PAGADO', 'COMPLETADO', 'PENDIENTE']),
            'created_at'    => $fechaSimulada,
            'updated_at'    => $fechaSimulada,
        ];
    }

    /**
     * Acciones que ocurren justo después de crear el Ticket
     */
    public function configure()
    {
        return $this->afterCreating(function (\App\Models\Ticket $ticket) {
            $cantidadItems = rand(2, 6);

            \App\Models\TicketItem::factory()
                ->count($cantidadItems)
                ->create([
                    'ticket_id'  => $ticket->id,
                    'created_at' => $ticket->created_at, // Vital: clonamos el tiempo histórico del padre
                    'updated_at' => $ticket->created_at,
                ]);
        });
    }
}

<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente a esta factory.
     */
    protected $model = Ticket::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_number' => $this->faker->unique()->numerify('TCK-########'),
            'seller_id'     => Seller::inRandomOrder()->value('id') ?? Seller::factory(),
            'total_amount'  => $this->faker->randomFloat(2, 50, 5000), // Monto entre 50.00 y 5000.00
            'issued_at'     => $this->faker->dateTimeBetween('-1 year', 'now'), // Fecha del último año
        ];
    }
}

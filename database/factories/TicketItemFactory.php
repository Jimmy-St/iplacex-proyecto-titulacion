<?php

namespace Database\Factories;

use App\Models\TicketItem;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketItem>
 */
class TicketItemFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente a esta factory.
     */
    protected $model = TicketItem::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_id'    => Ticket::inRandomOrder()->value('id') ?? Ticket::factory(),
            'product_code' => $this->faker->bothify('PRD-####'),
            'product_name' => $this->faker->words(3, true),
            'quantity'     => $quantity = $this->faker->numberBetween(1, 10),
            'price'        => $price = $this->faker->randomFloat(2, 5, 500),
            'subtotal'     => $quantity * $price,
        ];
    }
}

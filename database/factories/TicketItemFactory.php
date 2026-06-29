<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TicketItemFactory extends Factory
{
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 10);
        $price = $this->faker->randomFloat(2, 500, 15000); // Precios realistas en CLP/Decimal

        return [
            // 'ticket_id' lo inyectará dinámicamente el padre en el afterCreating
            'product_code' => 'PROD-' . $this->faker->unique()->numberBetween(1000, 9990),
            'product_name' => $this->faker->words(3, true),
            'quantity'     => $quantity,
            'price'        => $price,
            'subtotal'     => $quantity * $price, // <--- Matemática exacta para la consistencia
        ];
    }
}

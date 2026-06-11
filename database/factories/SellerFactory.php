<?php

namespace Database\Factories;

use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seller>
 */
class SellerFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente a esta factory.
     */
    protected $model = Seller::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_code' => $this->faker->unique()->numerify('EMP-####'),
            'first_name'    => $this->faker->firstName(),
            'last_name'     => $this->faker->lastName(),
            'is_active'     => $this->faker->boolean(80),
        ];
    }
}

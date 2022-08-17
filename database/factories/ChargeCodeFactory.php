<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChargeCode>
 */
class ChargeCodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "code" => randome_int(10000, 99999),
            "charge_amount" => randome_int(1000, 5000000),
            "amount_left" => randome_int(10, 1000)
        ];
    }
}

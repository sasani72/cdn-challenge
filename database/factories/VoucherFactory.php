<?php

namespace Database\Factories;

use App\Enums\VoucherTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voucher>
 */
class VoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'title'      => fake()->sentence(),
            'code'       => fake()->unique()->regexify('[A-Z]{5}[0-4]{3}'),
            'type'       => fake()->randomElement(VoucherTypes::values()),
            'amount'     => fake()->numberBetween(1000, 1000000),
            'max_uses'   => fake()->numberBetween(1, 1000),
            'current_uses' => 0,
            'starts_at'  => fake()->dateTimeBetween('now', '+1 hours')->format('Y-m-d H:i:s'),
            'expires_at' => fake()->dateTimeBetween('+2 hours', '+1 days')->format('Y-m-d H:i:s'),
            'description'    => fake()->sentence(),
        ];
    }
}

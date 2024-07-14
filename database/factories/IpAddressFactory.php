<?php

namespace Database\Factories;

use App\Models\IpAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IpAddress>
 */
class IpAddressFactory extends Factory
{
    protected $model = IpAddress::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' => fake()->word(),
            'ip_address' => fake()->ipv4(),
            'created_at' => fake()->dateTime('now'),
            'created_by' => 1,
        ];
    }
}

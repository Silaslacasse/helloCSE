<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->lastName,
            'firstName' => $this->faker->firstName,
            'imagePath' => $this->faker->imageUrl(400, 400, 'people'),
            'status' => $this->faker->randomElement(['inactive', 'pending', 'active']),
        ];
    }
}

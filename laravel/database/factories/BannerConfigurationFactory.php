<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\BannerConfiguration>
 */
class BannerConfigurationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'x_coordinate' => fake()->numberBetween(60, 300),
            'y_coordinate' => fake()->numberBetween(468, 1024),
            'text' => fake()->text(32),
            'font_size' => fake()->numberBetween(1, 5),
            'font_angle' => fake()->numberBetween(0, 360),
            'font_color_in_hexadecimal' => fake()->hexColor(),
        ];
    }
}

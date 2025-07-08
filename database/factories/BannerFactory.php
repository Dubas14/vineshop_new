<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BannerFactory extends Factory
{
    protected $model = \App\Models\Banner::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'subtitle' => $this->faker->sentence(),
            'image' => 'banner.jpg',
            'link_type' => 'custom',
            'link_target' => '#',
            'button_text' => 'Learn more',
            'is_active' => true,
        ];
    }
}

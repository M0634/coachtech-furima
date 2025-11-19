<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'categories' => json_encode([ $this->faker->numberBetween(1, 5) ]),
            'name' => $this->faker->word(),
            'image' => null,
            'img_url' => $this->faker->imageUrl(),
            'brand' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->numberBetween(300, 50000),
            'condition' => '良好',
            'status' => 'available',
            'image_path' => null,
        ];
    }
}

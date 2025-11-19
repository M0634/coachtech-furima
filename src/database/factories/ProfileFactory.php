<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition()
    {
        return [
            'user_id' => null, // テスト時に明示的に指定
            'name' => $this->faker->name,
            'postal_code' => $this->faker->postcode,
            'address' => $this->faker->address,
            'building' => $this->faker->secondaryAddress,
            'image' => null,
        ];
    }
}

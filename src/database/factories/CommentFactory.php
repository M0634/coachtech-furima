<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Comment;
use App\Models\User;
use App\Models\Item;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'item_id' => Item::factory(), // ★ item_id は Item::factory() にする
            'commentable_type' => Item::class,
            'commentable_id' => Item::factory(), // ★ ID だけなら factory() OK
            'content' => $this->faker->sentence,
        ];
    }

}

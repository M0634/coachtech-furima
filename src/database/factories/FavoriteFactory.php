<?php

namespace Database\Factories;

use App\Models\Favorite;
use App\Models\User;
use App\Models\Item;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class FavoriteFactory extends Factory
{
    protected $model = Favorite::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'favoritable_id' => Item::factory(), // デフォルトは Item
            'favoritable_type' => Item::class,   // Itemモデルを対象
        ];
    }

    /**
     * Product用の状態定義
     */
    public function forProduct()
    {
        return $this->state(function (array $attributes) {
            return [
                'favoritable_id' => Product::factory(),
                'favoritable_type' => Product::class,
            ];
        });
    }

    /**
     * 特定ユーザー向けに設定
     */
    public function forUser(User $user)
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id,
            ];
        });
    }

    /**
     * 特定アイテム向けに設定
     */
    public function forItem(Item $item)
    {
        return $this->state(function (array $attributes) use ($item) {
            return [
                'favoritable_id' => $item->id,
                'favoritable_type' => Item::class,
            ];
        });
    }

    /**
     * 特定プロダクト向けに設定
     */
    public function forProductId(Product $product)
    {
        return $this->state(function (array $attributes) use ($product) {
            return [
                'favoritable_id' => $product->id,
                'favoritable_type' => Product::class,
            ];
        });
    }
}

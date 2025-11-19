<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'img_url',
        'categories',
        'condition',
        'name',
        'brand',
        'description',
        'price',
    ];

    /**
     * モデルのイベントで user_id を自動設定
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (Auth::check() && ! $product->user_id) {
                $product->user_id = Auth::id();
            }
        });
    }

    /**
     * 出品者情報を取得
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 購入情報（購入履歴）を取得
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'item_id');
    }

    /**
     * お気に入り情報を取得
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    /**
     * Itemとの関連（必要に応じて）
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    /**
     * 🔍 検索用スコープ（ユーザー除外 + 共通商品を含む）
     *
     * 例: Product::visibleToUser()->get();
     */
    public function scopeVisibleToUser($query)
    {
        return $query->where(function ($query) {
            $query->where('user_id', '<>', Auth::id())
                ->orWhereNull('user_id')
                ->orWhere('user_id', 0); // 共通商品を含める
        });
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}

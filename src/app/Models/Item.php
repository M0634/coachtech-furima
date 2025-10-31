<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'description',
        'price',
        'status',
        'image_path',
    ];

    /** 出品者（ユーザー） */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** カテゴリー */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /** 購入履歴 */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /** お気に入り（多対一 → 中間テーブル favorite） */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /** コメント一覧 */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * ✅ 現在ログイン中のユーザーがこの商品を
     *    お気に入りしているかどうか判定するアクセサ
     */
    public function getIsFavoriteAttribute(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return $this->favorites()
            ->where('user_id', Auth::id())
            ->exists();
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }


}

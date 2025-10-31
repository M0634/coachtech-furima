<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',   // コメント内容
        'item_id',   // 商品ID（コメント対象）
        'user_id',   // ← これを追加することでエラー解消！
    ];

    // ユーザーとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 商品とのリレーション
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

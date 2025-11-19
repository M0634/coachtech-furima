<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',            // ← 追加（旧構成用）
        'commentable_type',
        'commentable_id',
        'content',
    ];

    /** コメントしたユーザー */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** 旧構成：Itemとの関係（互換用） */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /** 新構成：Item / Product 両対応 */
    public function commentable()
    {
        return $this->morphTo();
    }
}

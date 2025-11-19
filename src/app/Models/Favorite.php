<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'favoritable_id',
        'favoritable_type',
    ];

    /**
     * 多態リレーション
     */
    public function favoritable()
    {
        return $this->morphTo();
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Item;
use App\Models\User; // ← これが必要！！
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * コメント投稿
     * Item に対するコメントのみ対象（今後 product に拡張可）
     */
    public function store(CommentRequest $request, Item $item)
    {
        // 認証済みかつ DB にユーザー存在チェック
        $user = Auth::user();
        if (!$user || !User::where('id', $user->id)->exists()) {
            return redirect('/login');
        }

        // コメント作成
        Comment::create([
            'item_id'          => $item->id,
            'user_id'          => $user->id,
            'content'          => $request->validated()['content'],
            'commentable_type' => Item::class,
            'commentable_id'   => $item->id,
        ]);

        // "/" へ戻す
        return redirect('/')
            ->with('success', 'コメントを投稿しました。');
    }
}

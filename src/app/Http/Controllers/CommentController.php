<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Item $item)
    {
        Comment::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'content' => $request->validated()['content'],
        ]);

        return redirect()
            ->route('items.show', $item->id)
            ->with('success', 'コメントを投稿しました。');
    }
}


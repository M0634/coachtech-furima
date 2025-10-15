<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $profile = Auth::user()->profile()->first();
        return view('mypage.profile_edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'nullable|string|max:50',
            'postal_code' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('avatars', 'public');
            $data['image'] = $path;
        }

        $updateData = array_filter($data, fn($value) => !is_null($value));

        $profile = $user->profile()->updateOrCreate(
        ['user_id' => $user->id],
        array_filter($data, fn($v) => $v !== null && $v !== '')
        );

        return redirect()->route('mypage.profile.edit')->with('success', 'プロフィールを更新しました！');
    }
}
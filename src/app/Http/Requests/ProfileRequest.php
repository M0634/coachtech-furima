<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'profile_image' => 'nullable|file|mimes:jpeg,png',
            'username' => 'required|string|max:20',
            'postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'profile_image.mimes' => 'プロフィール画像はJPEGまたはPNG形式でアップロードしてください。',
            'username.required' => 'ユーザー名を入力してください。',
            'username.max' => 'ユーザー名は20文字以内で入力してください。',
            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code.regex' => '郵便番号はハイフンを含む8文字で入力してください（例：123-4567）。',
            'address.required' => '住所を入力してください。',
        ];
    }
}

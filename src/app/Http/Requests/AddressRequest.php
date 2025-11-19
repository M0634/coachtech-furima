<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address' => 'required|string',
            'building' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code.regex' => '郵便番号はハイフンを含む8文字で入力してください（例：123-4567）。',
            'address.required' => '住所を入力してください。',
        ];
    }
}

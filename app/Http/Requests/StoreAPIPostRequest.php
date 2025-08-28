<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAPIPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Open endpoint; still allow middleware to protect if needed.
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
        ];
    }
}

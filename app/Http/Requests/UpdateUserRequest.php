<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $userId = $this->route('user') ?? $this->route('userId') ?? null;

        return [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', "unique:users,email,{$userId}"],
            'password' => ['nullable', 'string', 'confirmed', 'min:8'],
        ];
    }
}

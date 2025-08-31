<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Controller will perform model authorization after fetching resource
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'exists:users,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'qty' => ['nullable', 'integer', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ];
    }
}

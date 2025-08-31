<?php

namespace App\Http\Requests;

use App\Models\Vehicle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && $this->user()->can('create', Vehicle::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'user_id' => ['nullable', 'exists:users,id'],
            'qty' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Sila masukkan nama kenderaan.',
            'qty.required' => 'Sila masukkan kuantiti.',
            'price.required' => 'Sila masukkan harga.',
            'user_id.exists' => 'Pemilik tidak sah.',
        ];
    }
}

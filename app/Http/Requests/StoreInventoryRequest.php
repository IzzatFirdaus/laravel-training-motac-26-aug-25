<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Inventory;

class StoreInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Use policy to determine if the user may create inventories
        return Auth::check() && $this->user()->can('create', Inventory::class);
    }

    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'qty' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Sila masukkan nama inventori.',
            'qty.required' => 'Sila masukkan kuantiti.',
            'price.required' => 'Sila masukkan harga.',
        ];
    }
}

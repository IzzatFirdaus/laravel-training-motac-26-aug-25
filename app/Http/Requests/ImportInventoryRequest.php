<?php

namespace App\Http\Requests;

use App\Models\Inventory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ImportInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && $this->user()->can('create', Inventory::class);
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:xlsx,csv,txt'],
        ];
    }
}

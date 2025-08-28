<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ImportInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && $this->user()->can('create', \App\Models\Inventory::class);
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:xlsx,csv,txt'],
        ];
    }
}

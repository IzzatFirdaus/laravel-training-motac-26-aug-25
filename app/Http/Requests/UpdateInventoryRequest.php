<?php

namespace App\Http\Requests;

use App\Models\Shelf;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Authorization will be checked in controller after locating the model
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'exists:users,id'],
            'warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'shelf_id' => ['nullable', 'exists:shelves,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'qty' => ['nullable', 'integer', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'vehicle_ids' => ['nullable', 'array'],
            'vehicle_ids.*' => ['integer', 'exists:vehicles,id'],
        ];
    }

    public function messages(): array
    {
        return [];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $wid = $this->input('warehouse_id');
            $sid = $this->input('shelf_id');
            if (!($wid && $sid)){
        return;} 
                $shelf = Shelf::find($sid);
                if (! $shelf || (string) $shelf->warehouse_id !== (string) $wid) {
                    $validator->errors()->add('shelf_id', 'Rak yang dipilih bukan sebahagian daripada gudang yang dipilih.');
                }
            
        });
    }
}

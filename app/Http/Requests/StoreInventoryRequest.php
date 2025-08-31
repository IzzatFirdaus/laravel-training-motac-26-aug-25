<?php

namespace App\Http\Requests;

use App\Models\Shelf;
use App\Models\Inventory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

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
            'warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'shelf_id' => ['nullable', 'exists:shelves,id'],
            'name' => ['required', 'string', 'max:255'],
            'qty' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'vehicle_ids' => ['nullable', 'array'],
            'vehicle_ids.*' => ['integer', 'exists:vehicles,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Sila masukkan nama inventori.',
            'qty.required' => 'Sila masukkan kuantiti.',
            'price.required' => 'Sila masukkan harga.',
            'warehouse_id.exists' => 'Gudang tidak sah.',
            'shelf_id.exists' => 'Rak tidak sah.',
        ];
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

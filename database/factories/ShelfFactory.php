<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Shelf;

class ShelfFactory extends Factory
{
    protected $model = Shelf::class;

    public function definition()
    {
        return [
            'warehouse_id' => null,
            'shelf_number' => strtoupper($this->faker->bothify('S-##')),
        ];
    }
}

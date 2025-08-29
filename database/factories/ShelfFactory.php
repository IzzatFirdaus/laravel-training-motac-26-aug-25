<?php

namespace Database\Factories;

use App\Models\Shelf;
use Illuminate\Database\Eloquent\Factories\Factory;

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

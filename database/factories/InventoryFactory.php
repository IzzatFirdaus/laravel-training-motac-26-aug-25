<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Inventory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory>
 */
class InventoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Inventory::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Use locale-specific faker for Malaysia to produce more realistic values
        $faker = \Faker\Factory::create('ms_MY');

        $brands = ['Dell', 'HP', 'Lenovo', 'Asus', 'Acer', 'Apple', 'Samsung'];
        $categories = ['Laptop', 'Monitor', 'Keyboard', 'Mouse', 'Router', 'Switch', 'Server', 'UPS'];
        $brand = $faker->randomElement($brands);
        $category = $faker->randomElement($categories);
        $model = $brand . ' ' . $faker->bothify('Model-###');
        $serial = strtoupper($faker->bothify('SN-??###'));

        // price ranges by category
        $priceRange = match ($category) {
            'Laptop' => [1500, 8000],
            'Monitor' => [300, 2000],
            'Keyboard' => [20, 300],
            'Mouse' => [10, 200],
            'Router' => [100, 1500],
            'Switch' => [150, 5000],
            'Server' => [5000, 50000],
            'UPS' => [200, 4000],
            default => [50, 1000],
        };

        $purchaseDate = $faker->dateTimeBetween('-5 years', 'now');
        $warrantyEnd = (clone $purchaseDate)->modify('+3 years');

        return [
            'user_id' => User::factory(),
            'name' => sprintf('%s %s', $category, $model),
            'qty' => $faker->numberBetween(1, 20),
            'price' => $faker->randomFloat(2, $priceRange[0], $priceRange[1]),
            'description' => implode("\n", [
                "Manufacturer: {$brand}",
                "Model: {$model}",
                "Category: {$category}",
                "Serial: {$serial}",
                "Lokasi: " . $faker->randomElement(['Pejabat KL', 'Pejabat JB', 'Gudang Kuching', 'Cawangan Penang']),
                "Tarikh Pembelian: " . $purchaseDate->format('Y-m-d'),
                "Tamat Waranti: " . $warrantyEnd->format('Y-m-d'),
            ]),
        ];
    }

    /**
     * State: produce inventory without an owner (user_id = null).
     */
    public function withoutOwner(): static
    {
        return $this->state(function (array $attributes): array {
            return ['user_id' => null];
        });
    }

    /**
     * State: mark inventory as used (reduce price, set condition).
     */
    public function asUsed(): static
    {
        return $this->state(function (array $attributes): array {
            $faker = \Faker\Factory::create('ms_MY');
            $condition = $faker->randomElement(['Used', 'Refurbished', 'Second-hand']);
            $oldPrice = $attributes['price'] ?? $faker->randomFloat(2, 50, 10000);
            $price = (float) max(0, $oldPrice * $faker->randomFloat(2, 0.2, 0.7));

            $description = $attributes['description'] ?? "Condition: {$condition}";
            if (preg_match('/Condition:\s*/', $description) === 1) {
                $description = preg_replace('/Condition: .*/', "Condition: {$condition}", $description);
            } else {
                $description .= "\nCondition: {$condition}";
            }

            return ['description' => $description, 'price' => $price];
        });
    }

    /**
     * State: mark inventory as new (standard price, condition).
     */
    public function asNew(): static
    {
        return $this->state(function (array $attributes): array {
            $faker = \Faker\Factory::create('ms_MY');
            $condition = $faker->randomElement(['New', 'Unused']);
            $price = $attributes['price'] ?? $faker->randomFloat(2, 100, 20000);

            $description = $attributes['description'] ?? "Condition: {$condition}";
            if (preg_match('/Condition:\s*/', $description) === 1) {
                $description = preg_replace('/Condition: .*/', "Condition: {$condition}", $description);
            } else {
                $description .= "\nCondition: {$condition}";
            }

            return ['description' => $description, 'price' => $price];
        });
    }
}

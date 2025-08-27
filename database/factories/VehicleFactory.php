<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Vehicle;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vehicle::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create('ms_MY');

        $makes = ['Perodua', 'Proton', 'Toyota', 'Honda', 'Nissan', 'BMW', 'Mercedes-Benz', 'Mazda'];
        $make = $faker->randomElement($makes);
        $model = $faker->randomElement(['X', 'S', 'V', 'Elite', 'Pro', 'GL', 'Sport', 'Plus']) . ' ' . $faker->bothify('###');
        $year = $faker->numberBetween(2005, 2025);

        // More accurate Malaysian plate generator.
        // Rules applied:
        // - Letters I, O and Z are omitted in sequences used for ordinary plates.
        // - Numbers are 1..9999 (no leading zeroes).
        // - Peninsular, Sabah (Sx), Sarawak (Qx) and special prefixes (KV, L, F) are supported.
        $lettersPool = array_values(array_diff(range('A', 'Y'), ['I', 'O', 'Z']));

        $region = $faker->randomElement(['peninsular', 'sabah', 'sarawak', 'labuan', 'putrajaya', 'langkawi']);

        $pickLetters = function (int $count) use ($faker, $lettersPool): string {
            $out = '';
            for ($i = 0; $i < $count; $i++) {
                $out .= $faker->randomElement($lettersPool);
            }
            return $out;
        };

        if ($region === 'langkawi') {
            // Langkawi uses KV #### (single suffix letter sometimes)
            $prefix = 'KV';
            $letters = '';
        } elseif ($region === 'labuan') {
            $prefix = 'L';
            $letters = $pickLetters($faker->numberBetween(0, 2));
        } elseif ($region === 'putrajaya') {
            $prefix = 'F' . $pickLetters(1); // FA, FB, FC etc
            $letters = $pickLetters($faker->numberBetween(0, 1));
        } elseif ($region === 'sarawak') {
            // Sarawak: Q + division letter (A..Y excluding I,O,Z) then optional letters
            $division = $faker->randomElement($lettersPool);
            $prefix = 'Q' . $division;
            $letters = $pickLetters($faker->numberBetween(0, 1));
        } elseif ($region === 'sabah') {
            // Sabah: S + division letter
            $division = $faker->randomElement($lettersPool);
            $prefix = 'S' . $division;
            $letters = $pickLetters($faker->numberBetween(0, 1));
        } else {
            // Peninsular: single-letter prefixes commonly used
            $penPrefixes = ['A','B','C','D','J','K','M','N','P','R','T','V','W'];
            $prefix = $faker->randomElement($penPrefixes);
            // Target total letter block length (including prefix) typically 2..3; pick remaining
            $remaining = $faker->randomElement([1,2]);
            $letters = $pickLetters($remaining);
            // If prefix is 'W' (extended series), sometimes single-letter followed by suffix later; keep simple.
        }

        // Compose the alpha block: prefix + letters (no spaces inside), then a space and number
        $alpha = strtoupper($prefix . $letters);
        $number = $faker->numberBetween(1, 9999); // no leading zeroes
        $reg = sprintf('%s %d', $alpha, $number);

        return [
            'user_id' => User::factory(),
            'name' => sprintf('%s %s (%s)', $make, $model, $reg),
            'qty' => 1,
            'price' => $faker->randomFloat(2, 15000, 300000),
            'description' => implode("\n", [
                "Make: {$make}",
                "Model: {$model}",
                "Year: {$year}",
                "Registration: {$reg}",
                "Condition: " . $faker->randomElement(['Used', 'New', 'Certified Pre-Owned']),
            ]),
        ];
    }

    /**
     * State: factory produces a vehicle without an owner (user_id = null).
     */
    public function withoutOwner(): static
    {
        return $this->state(function (array $attributes): array {
            return ['user_id' => null];
        });
    }

    /**
     * State: mark the vehicle as used and adjust some fields accordingly.
     */
    public function asUsed(): static
    {
        return $this->state(function (array $attributes): array {
            $faker = \Faker\Factory::create('ms_MY');
            $condition = $faker->randomElement(['Used', 'Good - Used', 'Second-hand']);
            $oldPrice = $attributes['price'] ?? $faker->randomFloat(2, 5000, 150000);
            $price = (float) max(0, $oldPrice * $faker->randomFloat(2, 0.45, 0.8));

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
     * State: mark the vehicle as new and adjust price/description accordingly.
     */
    public function asNew(): static
    {
        return $this->state(function (array $attributes): array {
            $faker = \Faker\Factory::create('ms_MY');
            $condition = $faker->randomElement(['New', 'Brand New', 'Factory Fresh']);
            $price = $attributes['price'] ?? $faker->randomFloat(2, 20000, 300000);

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

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name,
            'mother_name' => $this->faker->name,
            'birth_date' => $this->faker->date(),
            'cpf' => $this->faker->unique()->numberBetween(10000000000, 99999999999),
            'cns' => $this->faker->unique()->numberBetween(100000000000000, 999999999999999),
            'picture' => $this->faker->imageUrl(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (\App\Models\Patient $patient) {
            $patient->address()->create([
                'zip_code' => $this->faker->postcode,
                'street' => $this->faker->streetName,
                'number' => $this->faker->buildingNumber,
                'complement' => $this->faker->secondaryAddress,
                'district' => $this->faker->city,
                'city' => $this->faker->city,
                'state' => $this->faker->stateAbbr,
            ]);
        });
    }
}

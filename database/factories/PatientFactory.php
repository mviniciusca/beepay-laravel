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
            'cpf' => str_replace(['.', '-'], '', $this->faker->unique()->cpf),
            'cns' => $this->faker->unique()->numerify('###############'),
            'picture' => $this->faker->imageUrl(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (\App\Models\Patient $patient) {
            $patient->address()->create([
                'patient_id' => $patient->id,
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

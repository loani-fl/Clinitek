<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Medico>
 */
class MedicoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */


    public function definition(): array
    {
        return [
            'nombre' => $this->faker->firstName(),
            'apellidos' => $this->faker->lastName(),
            'especialidad' => $this->faker->randomElement(['Cardiología', 'Pediatría', 'Dermatología', 'Neurología', 'Oncología']),
            'telefono' => $this->faker->phoneNumber(),
            'correo' => $this->faker->unique()->safeEmail(),
            'salario' => $this->faker->randomFloat(2, 1500, 50000),
            'identidad' => $this->faker->unique()->numerify('#############'),
            'fecha_nacimiento' => $this->faker->dateTimeBetween('1950-01-01', '2005-12-31')->format('Y-m-d'),
            'fecha_ingreso' => $this->faker->dateTimeBetween('2000-01-01', 'now')->format('Y-m-d'),
            'genero' => $this->faker->randomElement(['Masculino', 'Femenino', 'Otro']),
            'observaciones' => $this->faker->optional()->text(100),
        ];
    }
}

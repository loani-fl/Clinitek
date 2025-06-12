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
            'fecha_nacimiento' => $this->faker->date('Y-m-d', '1980-01-01'), // hasta 1980-01-01
            'fecha_ingreso' => $this->faker->date('Y-m-d', 'now'), // fecha hasta hoy
            'genero' => $this->faker->randomElement(['Masculino', 'Femenino', 'Otro']),
            'observaciones' => $this->faker->optional()->text(100),
        ];
    }
}

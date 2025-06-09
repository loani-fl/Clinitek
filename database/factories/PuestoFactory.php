<?php

use Illuminate\Database\Eloquent\Factories\Factory;

class PuestoFactory extends Factory
{
    public function definition(): array
    {
        $areas = ['Administración', 'Recepción', 'Laboratorio', 'Farmacia', 'Enfermería', 'Mantenimiento'];

        return [
            'nombre' => $this->faker->unique()->jobTitle,
            'codigo' => strtoupper($this->faker->unique()->bothify('P###')),
            'area' => $this->faker->randomElement($areas),
            'sueldo' => $this->faker->numberBetween(11000, 20000),
            'funcion' => $this->faker->sentence(12),
        ];
    }
}


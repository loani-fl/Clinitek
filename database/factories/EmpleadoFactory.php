<?php

namespace Database\Factories;
use App\Models\Puesto;
use Illuminate\Database\Eloquent\Factories\Factory;


class EmpleadoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombres' => $this->faker->firstName(),
            'apellidos' => $this->faker->lastName(),
            'identidad'         => $this->faker->unique()->numerify('0801#########'),
            'direccion'         => $this->faker->address(),
            'telefono'          => $this->faker->numerify('9###-####'),
            'correo'            => $this->faker->unique()->safeEmail(),
            'fecha_ingreso'     => $this->faker->date('Y-m-d'),
            'fecha_nacimiento'  => $this->faker->date('Y-m-d', '2002-01-01'),
            'genero'            => $this->faker->randomElement(['Masculino', 'Femenino', 'Otro']),
            'estado_civil'      => $this->faker->randomElement(['Soltero', 'Casado', 'Divorciado', 'Viudo']),
            'puesto_id'         => Puesto::inRandomOrder()->first()->id ?? 1, 
            'salario'           => $this->faker->randomFloat(2, 10000, 30000),
            'observaciones'     => $this->faker->sentence(6),
        ];
    }
}

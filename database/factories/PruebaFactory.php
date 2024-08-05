<?php

namespace Database\Factories;

use App\Models\Prueba;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PruebaFactory extends Factory
{
    protected $model = Prueba::class;

    public function definition()
    {
        return [
			'personal_id' => $this->faker->name,
			'capacitacion_id' => $this->faker->name,
			'puntaje' => $this->faker->name,
			'correctas' => $this->faker->name,
			'incorrectas' => $this->faker->name,
			'fecha_inicio' => $this->faker->name,
			'fecha_fin' => $this->faker->name,
			'duracion' => $this->faker->name,
			'status_id' => $this->faker->name,
        ];
    }
}

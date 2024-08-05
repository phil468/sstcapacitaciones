<?php

namespace Database\Factories;

use App\Models\Asignacione;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AsignacioneFactory extends Factory
{
    protected $model = Asignacione::class;

    public function definition()
    {
        return [
			'personal_id' => $this->faker->name,
			'capacitacion_id' => $this->faker->name,
			'fecha_inicio' => $this->faker->name,
			'fecha_fin' => $this->faker->name,
			'intentos_de_evaluacion' => $this->faker->name,
			'realizado' => $this->faker->name,
			'finalizado' => $this->faker->name,
			'created_by' => $this->faker->name,
			'updated_by' => $this->faker->name,
			'deleted_by' => $this->faker->name,
        ];
    }
}

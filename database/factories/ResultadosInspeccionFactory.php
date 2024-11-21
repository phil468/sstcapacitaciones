<?php

namespace Database\Factories;

use App\Models\ResultadosInspeccion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ResultadosInspeccionFactory extends Factory
{
    protected $model = ResultadosInspeccion::class;

    public function definition()
    {
        return [
			'inspeccion_id' => $this->faker->name,
			'descripcion' => $this->faker->name,
			'nivel_riesgo' => $this->faker->name,
			'registro_fotografico' => $this->faker->name,
			'accion_a_tomar' => $this->faker->name,
			'responsable_id' => $this->faker->name,
			'estado' => $this->faker->name,
			'fecha_ejecucion' => $this->faker->name,
        ];
    }
}

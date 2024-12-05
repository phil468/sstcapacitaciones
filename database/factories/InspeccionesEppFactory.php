<?php

namespace Database\Factories;

use App\Models\InspeccionesEpp;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InspeccionesEppFactory extends Factory
{
    protected $model = InspeccionesEpp::class;

    public function definition()
    {
        return [
			'numero_inspeccion' => $this->faker->name,
			'inspector' => $this->faker->name,
			'firma_inspector' => $this->faker->name,
			'turno' => $this->faker->name,
			'condicion' => $this->faker->name,
			'riesgo' => $this->faker->name,
			'actividad' => $this->faker->name,
			'fecha' => $this->faker->name,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\AlertasLevantamiento;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AlertasLevantamientoFactory extends Factory
{
    protected $model = AlertasLevantamiento::class;

    public function definition()
    {
        return [
			'resultado_inspeccion_id' => $this->faker->name,
			'registro_fotografico' => $this->faker->name,
			'levantado' => $this->faker->name,
        ];
    }
}

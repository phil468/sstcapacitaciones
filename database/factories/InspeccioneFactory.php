<?php

namespace Database\Factories;

use App\Models\Inspeccione;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InspeccioneFactory extends Factory
{
    protected $model = Inspeccione::class;

    public function definition()
    {
        return [
			'empresa_id' => $this->faker->name,
			'area_id' => $this->faker->name,
			'tipo_inspeccion' => $this->faker->name,
			'vigencia_inicio' => $this->faker->name,
			'vigencia_fin' => $this->faker->name,
			'comentario' => $this->faker->name,
        ];
    }
}

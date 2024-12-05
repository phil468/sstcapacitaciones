<?php

namespace Database\Factories;

use App\Models\InspeccionesGabinete;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InspeccionesGabineteFactory extends Factory
{
    protected $model = InspeccionesGabinete::class;

    public function definition()
    {
        return [
			'fecha_inspeccion' => $this->faker->name,
			'hora_inspeccion' => $this->faker->name,
			'inspector' => $this->faker->name,
			'lugar' => $this->faker->name,
        ];
    }
}

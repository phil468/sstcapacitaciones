<?php

namespace Database\Factories;

use App\Models\InspeccionArea;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InspeccionAreaFactory extends Factory
{
    protected $model = InspeccionArea::class;

    public function definition()
    {
        return [
			'inspeccion_id' => $this->faker->name,
			'area_id' => $this->faker->name,
        ];
    }
}

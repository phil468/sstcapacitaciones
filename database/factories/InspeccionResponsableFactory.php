<?php

namespace Database\Factories;

use App\Models\InspeccionResponsable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InspeccionResponsableFactory extends Factory
{
    protected $model = InspeccionResponsable::class;

    public function definition()
    {
        return [
			'inspeccion_id' => $this->faker->name,
			'user_id' => $this->faker->name,
			'cargo' => $this->faker->name,
        ];
    }
}

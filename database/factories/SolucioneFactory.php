<?php

namespace Database\Factories;

use App\Models\Solucione;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SolucioneFactory extends Factory
{
    protected $model = Solucione::class;

    public function definition()
    {
        return [
			'pregunta_id' => $this->faker->name,
			'opcion_id' => $this->faker->name,
        ];
    }
}

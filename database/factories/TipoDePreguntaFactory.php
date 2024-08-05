<?php

namespace Database\Factories;

use App\Models\TipoDePregunta;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TipoDePreguntaFactory extends Factory
{
    protected $model = TipoDePregunta::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'estado' => $this->faker->name,
        ];
    }
}

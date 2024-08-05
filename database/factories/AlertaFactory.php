<?php

namespace Database\Factories;

use App\Models\Alerta;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AlertaFactory extends Factory
{
    protected $model = Alerta::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'estado' => $this->faker->name,
        ];
    }
}

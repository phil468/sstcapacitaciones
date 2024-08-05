<?php

namespace Database\Factories;

use App\Models\ModelHasAlerta;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ModelHasAlertaFactory extends Factory
{
    protected $model = ModelHasAlerta::class;

    public function definition()
    {
        return [
			'model_type' => $this->faker->name,
			'model_id' => $this->faker->name,
			'value' => $this->faker->name,
			'alerta_id' => $this->faker->name,
        ];
    }
}

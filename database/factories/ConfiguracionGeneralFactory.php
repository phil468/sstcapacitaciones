<?php

namespace Database\Factories;

use App\Models\ConfiguracionGeneral;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ConfiguracionGeneralFactory extends Factory
{
    protected $model = ConfiguracionGeneral::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'valor' => $this->faker->name,
			'tipo_de_dato_id' => $this->faker->name,
			'created_by' => $this->faker->name,
			'updated_by' => $this->faker->name,
			'deleted_by' => $this->faker->name,
        ];
    }
}

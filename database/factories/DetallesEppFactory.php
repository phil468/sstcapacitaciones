<?php

namespace Database\Factories;

use App\Models\DetallesEpp;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DetallesEppFactory extends Factory
{
    protected $model = DetallesEpp::class;

    public function definition()
    {
        return [
			'inspeccion_id' => $this->faker->name,
			'item' => $this->faker->name,
			'nombre_trabajador' => $this->faker->name,
			'dni' => $this->faker->name,
			'cargo' => $this->faker->name,
			'casco_tiene' => $this->faker->name,
			'casco_uso' => $this->faker->name,
			'casco_condicion' => $this->faker->name,
			'zapatos_tiene' => $this->faker->name,
			'zapatos_uso' => $this->faker->name,
			'zapatos_condicion' => $this->faker->name,
			'lentes_tiene' => $this->faker->name,
			'lentes_uso' => $this->faker->name,
			'lentes_condicion' => $this->faker->name,
			'respirador_tiene' => $this->faker->name,
			'respirador_uso' => $this->faker->name,
			'respirador_condicion' => $this->faker->name,
			'protector_auditivo_tiene' => $this->faker->name,
			'protector_auditivo_uso' => $this->faker->name,
			'protector_auditivo_condicion' => $this->faker->name,
			'guantes_tiene' => $this->faker->name,
			'guantes_uso' => $this->faker->name,
			'guantes_condicion' => $this->faker->name,
			'otros' => $this->faker->name,
        ];
    }
}

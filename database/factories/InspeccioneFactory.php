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
			'razon_social' => $this->faker->name,
			'ruc' => $this->faker->name,
			'domicilio' => $this->faker->name,
			'actividad_economica' => $this->faker->name,
			'numero_registro' => $this->faker->name,
			'tipo_inspeccion_otro' => $this->faker->name,
			'fecha_inspeccion' => $this->faker->name,
			'hora_inspeccion' => $this->faker->name,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Gabinete;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GabineteFactory extends Factory
{
    protected $model = Gabinete::class;

    public function definition()
    {
        return [
			'numero_gabinete' => $this->faker->name,
			'ubicacion' => $this->faker->name,
			'inspeccion_id' => $this->faker->name,
			'enrollada_correctamente' => $this->faker->name,
			'acoples_estado' => $this->faker->name,
			'limpieza_manguera' => $this->faker->name,
			'empaques_estado' => $this->faker->name,
			'pintura_gabinete' => $this->faker->name,
			'limpieza_gabinete' => $this->faker->name,
			'vidrio_estado' => $this->faker->name,
			'senalizacion' => $this->faker->name,
			'piton_obstruido' => $this->faker->name,
			'piton_estado' => $this->faker->name,
			'valvula_principal_estado' => $this->faker->name,
			'valvula_principal_abierta' => $this->faker->name,
			'manometro_estado' => $this->faker->name,
			'valvula_angular_estado' => $this->faker->name,
			'observaciones' => $this->faker->name,
        ];
    }
}

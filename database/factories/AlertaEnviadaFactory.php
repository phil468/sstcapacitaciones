<?php

namespace Database\Factories;

use App\Models\AlertaEnviada;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AlertaEnviadaFactory extends Factory
{
    protected $model = AlertaEnviada::class;

    public function definition()
    {
        return [
			'capacitacion_has_personal_id' => $this->faker->name,
			'fecha_envio' => $this->faker->name,
        ];
    }
}

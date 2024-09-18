<?php

namespace Database\Factories;

use App\Models\NotificacionesEnviada;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NotificacionesEnviadaFactory extends Factory
{
    protected $model = NotificacionesEnviada::class;

    public function definition()
    {
        return [
			'capacitacion_id' => $this->faker->name,
			'personal_id' => $this->faker->name,
        ];
    }
}

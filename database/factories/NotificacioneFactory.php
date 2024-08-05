<?php

namespace Database\Factories;

use App\Models\Notificacione;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NotificacioneFactory extends Factory
{
    protected $model = Notificacione::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'estado' => $this->faker->name,
        ];
    }
}

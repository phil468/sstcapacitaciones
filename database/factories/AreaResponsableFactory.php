<?php

namespace Database\Factories;

use App\Models\AreaResponsable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AreaResponsableFactory extends Factory
{
    protected $model = AreaResponsable::class;

    public function definition()
    {
        return [
			'area_id' => $this->faker->name,
			'personal_id' => $this->faker->name,
        ];
    }
}

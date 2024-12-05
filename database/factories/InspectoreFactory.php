<?php

namespace Database\Factories;

use App\Models\Inspectore;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InspectoreFactory extends Factory
{
    protected $model = Inspectore::class;

    public function definition()
    {
        return [
			'personal_id' => $this->faker->name,
			'estado' => $this->faker->name,
        ];
    }
}

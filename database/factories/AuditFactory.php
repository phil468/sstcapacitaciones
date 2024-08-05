<?php

namespace Database\Factories;

use App\Models\Audit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AuditFactory extends Factory
{
    protected $model = Audit::class;

    public function definition()
    {
        return [
			'user_type' => $this->faker->name,
			'user_id' => $this->faker->name,
			'event' => $this->faker->name,
			'auditable_type' => $this->faker->name,
			'auditable_id' => $this->faker->name,
			'old_values' => $this->faker->name,
			'new_values' => $this->faker->name,
			'url' => $this->faker->name,
			'ip_address' => $this->faker->name,
			'user_agent' => $this->faker->name,
			'tags' => $this->faker->name,
        ];
    }
}

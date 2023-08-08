<?php

namespace Database\Factories;

use App\Models\Technology;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'backend_id' => Technology::firstOrCreate([
                'name' => Arr::random(config('constants.technologies.backend'))
            ])->id,
            'frontend_id' => Technology::firstOrCreate([
                'name' => Arr::random(config('constants.technologies.frontend'))
            ])->id,
            'database_id' => Technology::firstOrCreate([
                'name' => Arr::random(config('constants.technologies.database'))
            ])->id,
        ];
    }
}

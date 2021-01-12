<?php

namespace Database\Factories;

use App\Models\Dependency;
use Illuminate\Database\Eloquent\Factories\Factory;

class DependencyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Dependency::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\EventType;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EventType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->word;

        return [
            'name' => $name,
            'key' => strtolower($name),
            'fields' => [
                [
                    'label' => $this->faker->lexify('???????????'),
                    'type' => 'string',
                ],
                [
                    'label' => $this->faker->numerify('####'),
                    'type' => 'number',
                ],
                [
                    'label' => $this->faker->numerify('##/##/####'),
                    'type' => 'date',
                ]
            ]
        ];
    }
}

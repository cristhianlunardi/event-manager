<?php

namespace Database\Seeders;

use App\Models\Dependency;
use App\Models\Event;
use App\Models\EventType;
use App\Models\User;
use DateTime;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Event::create([
            'title' => 'Consejo de Facultad #24',
            'startDate' => new DateTime('17-02-2021'),
            'dependency' => Dependency::all()->random()->id,
            'eventType' => EventType::where('name', 'Consejo de Facultad')->first()->id,
            //'featuredImage' => 'image.png',
            'eventTypeFields' => [
                [
                    'label' => 'Abierto al público',
                    'type' => 'bool',
                    'value' => true,
                ],
                [
                    'label' => 'Tópicos a tratar',
                    'type' => 'list',
                    'value' => ['Elecciones estudiantiles', 'Reforma de pensum Computación', 'Nuevos ingreso'],
                ],
            ],
            'additionalFields' => [
                [
                    'label' => 'Hora de descanso',
                    'type' => 'date',
                    'value' => new DateTime('17-02-2021'),
                ],
                [
                    'label' => 'Número de puestos',
                    'type' => 'number',
                    'value' => 32,
                ]
            ],
            'agreements' => [
                // Podrían ir en un solo array (?)
                [
                    'text' => 'Lorem ipsum Lorem ipsum  Lorem ipsum  Lorem ipsum Lorem ipsum'
                ],
                [
                    'text' => 'Imrem Lopsum Imrem Lopsum Imrem Lopsum Imrem Lopsum Imrem Lopsum Imrem Lopsum'
                ],
            ],
            'participants' => [
                // Verificar si existen usuarios repetidos entre la lista
                [
                    'userId' => User::all()->random()->id,
                    'canEdit' => true,
                ],
                [
                    'userId' => User::all()->random()->id,
                    'canEdit' => true,
                ],
                [
                    'userId' => User::all()->random()->id,
                    'canEdit' => false,
                ]
            ]
        ]);
    }
}

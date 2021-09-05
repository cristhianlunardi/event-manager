<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventType;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //\App\Models\EventType::factory(2)->create();

        $name = 'Maratón de Programación';
        $key = strtolower($name);
        EventType::create([
            'name' => $name,
            'key' => $key,
            'fields' => [
                [
                    'label' => 'Número de equipos',
                    'type' => 'integer',
                ],
                [
                    'label' => 'Dificultad',
                    'type' => 'string',
                ],
                [
                    'label' => 'Miembros por equipo',
                    'type' => 'integer',
                ],
                [
                    'label' => 'Tiempo total',
                    'type' => 'string',
                ]
            ]
        ]);

        $name = 'Consejo de Facultad';
        $key = strtolower($name);
        EventType::create([
            'name' => $name,
            'key' => $key,
            'fields' => [
                [
                    'label' => 'Lugar',
                    'type' => 'string',
                ],
                [
                    'label' => 'Abierto al público',
                    'type' => 'bool',
                ],
                [
                    'label' => 'Tópicos a tratar',
                    'type' => 'list',
                ]
            ]
        ]);
    }
}

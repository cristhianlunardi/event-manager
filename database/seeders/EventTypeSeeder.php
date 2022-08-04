<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\EventType::factory(10)->create();
    }
}

<?php

namespace Database\Seeders;

use App\Models\Dependency;
use Illuminate\Database\Seeder;

class DependencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        # \App\Models\Dependency::factory(2)->create();
        Dependency::create(['name' => 'Computación', 'key' => 'computación']);
        Dependency::create(['name' => 'Biología', 'key' => 'biología']);
        Dependency::create(['name' => 'Física', 'key' => 'física']);
        Dependency::create(['name' => 'Matemática', 'key' => 'matemática']);
        Dependency::create(['name' => 'Sin Dependencia', 'key' => 'sin dependencia']);
    }
}

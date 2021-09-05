<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'Estándar', 'key' => 'estándar']);
        Role::create(['name' => 'Profesor', 'key' => 'profesor']);
        Role::create(['name' => 'Coordinador', 'key' => 'coordinador']);
        Role::create(['name' => 'Decano', 'key' => 'decano']);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Dependency;
use App\Models\Role;
use App\Models\User;
use DateTime;
use Illuminate\Database\Seeder;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'cristhian@event.com',
            'password' => 'c123456',
            'full_name' => 'Cristhian Lunardi',
            'birthdate' => new DateTime('01-01-2000'),
            'dependency' => Dependency::all()->toArray(),
            'role' => [Role::where('name', 'Admin')->first()->toArray()],
            'isActive' => true,
            'id_number' => '25111333',
            'rif' => '251113334',
            'user_type' => 'Persona',
        ]);

        User::create([
            'email' => 'carlos@event.com',
            'password' => 'c123456',
            'full_name' => 'Carlos Callañaupa',
            'birthdate' => new DateTime('02-02-2002'),
            'dependency' => [Dependency::all()->first()->toArray()],
            'role' => [Role::all()->first()->toArray()],
            'isActive' => true,
            'id_number' => '23555666',
            'rif' => '235556667',
            'user_type' => 'Empresa',
        ]);
    }
}

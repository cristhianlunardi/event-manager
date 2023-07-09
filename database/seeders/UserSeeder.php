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
            'fullName' => 'Cristhian Lunardi',
            'birthday' => new DateTime('01-01-2000'),
            'dependency' => Dependency::all()->toArray(),
            'role' => Role::all()->toArray(),// [Role::where('name', 'Admin')->first()->toArray()],
            'isActive' => true,
        ]);

        User::create([
            'email' => 'carlos@event.com',
            'password' => 'c123456',
            'fullName' => 'Carlos Callañaupa',
            'birthday' => new DateTime('02-02-2002'),
            'dependency' => [Dependency::all()->first()->toArray()],
            'role' => [Role::all()->first()->toArray()],
            'isActive' => true,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Dependency;
use App\Models\Role;
use App\Models\User;
use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

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
            'birthday' => new DateTime('14-07-1995'),
            'dependency' => [Dependency::all()->random()->id],
            'role' => Role::where('name', 'Admin')->first()->id,
            'isActive' => true,
        ]);

        User::create([
            'email' => 'carlos@event.com',
            'password' => 'c123456',
            'fullName' => 'Carlos Callañaupa',
            'birthday' => new DateTime('22-07-1994'),
            'dependency' => [Dependency::all()->random()->id],
            'role' => Role::all()->random()->id,
            'isActive' => true,
        ]);
    }
}

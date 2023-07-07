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
        Role::create([
            'name' => 'Admin', 'key' => 'admin',
            'permissions' => [
                'create_dependency', 'read_dependency', 'update_dependency', 'delete_dependency',
                'create_user', 'view_user', 'update_user', 'delete_user',
            ]
        ]);
        Role::create([
            'name' => 'Decano', 'key' => 'decano',
            'permissions' => [
                'create_dependency', 'read_dependency', 'update_dependency', 'delete_dependency'
            ]
        ]);
        Role::create([
            'name' => 'Coordinador', 'key' => 'coordinador',
            'permissions' => [
                'create_dependency', 'read_dependency', 'update_dependency', 'delete_dependency'
            ]
        ]);
        Role::create([
            'name' => 'Profesor', 'key' => 'profesor',
            'permissions' => [
                'create_dependency', 'read_dependency', 'update_dependency',
                'read_user', 'create_user',
            ]
        ]);
        Role::create([
            'name' => 'Secretaria', 'key' => 'secretaria',
            'permissions' => [
                'create_dependency', 'read_dependency',
                'read_user',
            ]
        ]);
        Role::create([
            'name' => 'Estándar', 'key' => 'estándar',
            'permissions' => ['read_dependency']
        ]);
    }

    /* All Permissions
     * 'create_dependency', 'read_dependency', 'update_dependency', 'delete_dependency'
     * 'create_user', 'read_user', 'update_user', 'delete_user'
     */
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use const App\DEFAULT_NO_ROLE_NAME;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'key',
    ];

    protected $hidden = [
        '_id',
        'key',
        'updated_at',
        'created_at',
    ];

    public static function getNameFromId($id): string
    {
        $role = Role::where('_id', $id)->first();

        if (empty($role))
        {
            return DEFAULT_NO_ROLE_NAME;
        }

        return $role->name;
    }

    public static function getIdFromName($name): string
    {
        $role = Role::where('key', mb_strtolower($name))->first();

        if (empty($role))
        {
            return Role::getDefaultId();
        }

        return $role->id;
    }

    public static function getDefaultId()
    {
        $role = Role::where('key', mb_strtolower(DEFAULT_NO_ROLE_NAME))->first();

        return $role->id;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use const App\DEFAULT_NO_DEPENDENCY_NAME;

class Dependency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'key',
    ];

    protected $hidden = [
        'key',
        'created_at',
        'updated_at',
    ];

    public static function getNameFromId($id): string
    {
        $dependency = Dependency::where('_id', $id)->first();

        if (empty($dependency))
        {
            return DEFAULT_NO_DEPENDENCY_NAME;
        }

        return $dependency->name;
    }

    public static function getIdFromName($name): string
    {
        $dependency = Dependency::where('key', mb_strtolower($name))->first();

        if (empty($dependency))
        {
            return Dependency::getDefaultId();
        }

        return $dependency->id;
    }

    public static function getDependenciesFromNames($dependenciesArray)
    {
        $result = Dependency::whereIn('name', $dependenciesArray)->get();

        return $result;
    }

    public static function getDefaultId()
    {
        $dependency = Dependency::where('key', mb_strtolower(DEFAULT_NO_DEPENDENCY_NAME))->first();

        return $dependency->id;
    }

    public static function getUserDependencies($user) {
        return Dependency::whereIn('_id', $user->dependencies)->get();
    }
}

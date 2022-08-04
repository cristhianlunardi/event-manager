<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use const App\DEFAULT_NO_DEPENDENCY;

class Dependency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'key',
    ];

    protected $hidden = [
        '_id',
    ];

    public function findById($id) : string {
        $dependency = Dependency::where('id', $id);

        if (!$dependency)
        {
            return DEFAULT_NO_DEPENDENCY;
        }

        return $dependency->name;
    }

    public function getDefaultId() {
        $dependency = Dependency::where('key', 'sin dependencia');

        return $dependency->id;
    }
}

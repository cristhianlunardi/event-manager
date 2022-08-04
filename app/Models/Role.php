<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

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
}

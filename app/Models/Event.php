<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Relations\EmbedsOne;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'startDate',
        'dependency',
        'author',
        'description',
        'image',
        'eventType',
        'eventTypeFields',
        'additionalFields',
        'agreements',
        'participants',
    ];

    protected $hidden = [
        '_id',
    ];
}

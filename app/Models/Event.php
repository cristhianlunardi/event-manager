<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'startDate',
        'dependency',
        'eventType',
        'eventTypeFields',
        'additionalFields',
        'agreements',
        'participants',
    ];

    protected $hidden = [
    ];
}

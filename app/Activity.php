<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'type', 'distance', 'speed', 'date', 'duration', 'id_user'
    ];
}

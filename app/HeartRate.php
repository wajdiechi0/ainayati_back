<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HeartRate extends Model
{
    protected $fillable = [
        'heart_rate', 'date_time', 'user_email'
    ];
}

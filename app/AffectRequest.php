<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AffectRequest extends Model
{
    protected $fillable = [
        'id_patient', 'id_doctor',
    ];
}

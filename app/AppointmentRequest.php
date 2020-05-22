<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppointmentRequest extends Model
{
    protected $fillable = [
        'id_doctor', 'id_patient',
    ];
}

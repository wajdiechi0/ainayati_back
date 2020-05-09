<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AffectDoctorPatient extends Model
{
    protected $fillable = [
        'id_patient', 'id_doctor',
    ];
}

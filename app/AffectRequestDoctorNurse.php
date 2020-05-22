<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AffectRequestDoctorNurse extends Model
{
    protected $fillable = [
        'id_doctor', 'id_nurse',
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AffectDoctorNurse extends Model
{
    protected $fillable = [
        'id_doctor', 'id_nurse',
    ];
}

<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class UserRole extends Authenticatable
{
    use HasApiTokens,Notifiable;

    protected $fillable = [
        'id_user', 'id_role',
    ];
}

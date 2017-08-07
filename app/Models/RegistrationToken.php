<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationToken extends Model
{
    protected $table = 'registration_tokens';

    protected $fillable = [
        'email', 'token'
    ];
}
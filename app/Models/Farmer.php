<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    protected $fillable = [
        'full_name',
        'phone_number',
        'region',
        'village',
        'preferred_language',
        'wants_sms',
    ];
}

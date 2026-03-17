<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'phone_number',
        'region',
        'village',
        'preferred_language',
        'wants_sms',
    ];

    protected $casts = [
        'wants_sms' => 'boolean',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'region',
        'village',
        'crop_type',
        'pest_detected',
        'rainfall_status',
        'crop_condition',
        'market_price',
        'notes',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataSource extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status', 'last_updated_at'];

    protected $casts = [
        'last_updated_at' => 'datetime',
    ];
}

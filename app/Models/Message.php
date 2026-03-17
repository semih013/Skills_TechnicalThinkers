<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'recipient_group', 'sent_at', 'status'];

    protected $casts = [
        'sent_at' => 'datetime',
    ];
}

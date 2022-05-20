<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    public $table = 'rooms';

    public $guarded = [];

    public $casts = [
        'hours' => 'array',
    ];
}

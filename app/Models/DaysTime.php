<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaysTime extends Model
{
    use HasFactory;

    public $table = 'days_times';
    
    public $guarded = [];

    public $casts = [
        'hours' => 'array',
    ];

}

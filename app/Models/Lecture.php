<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    use HasFactory;

    public $table = 'lectures';

    public $guarded = [];

    public $casts = [
        'data' => 'array',
    ];
}

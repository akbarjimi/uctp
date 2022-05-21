<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Days extends Model
{
    public $table = 'days';

    public $guarded = [];

    public $casts = [
        "odd" => "boolean",
        "evening" => "boolean",
    ];
}

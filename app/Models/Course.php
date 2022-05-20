<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    public $table = 'courses';

    public $guarded = [];

    public $casts = [
        'need' => 'array',
    ];

    public function type()
    {
        return $this->belongsTo(CourseType::class, "course_type_id","id","type");
    }
}

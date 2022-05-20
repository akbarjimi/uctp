<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseType extends Model
{
    use HasFactory;

    public $table = 'course_types';

    public $guarded = [];

    public function courses()
    {
        return $this->hasMany(Course::class, "course_type_id","id");
    }
}

<?php

namespace Database\Seeders;

use App\Models\StudentYear;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentYearSeeder extends Seeder
{
    public function run()
    {
        $student_years = [
            [0, "دانشجوی سال اول"],
            [1, "دانشجوی سال دوم"],
            [2, "دانشجوی سال سوم"],
            [3, "دانشجوی سال چهارم"],
        ];

        foreach ($student_years as $student_year) {
            StudentYear::create([
                "name" => $student_year[1]
            ]);
        }
    }
}

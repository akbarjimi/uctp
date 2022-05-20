<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $general = [
            [0, 0, -1, []],
            [1, 0, -1, []],
            [2, 0, -1, []],
            [3, 0, -1, []],
            [4, 0, -1, []],
            [5, 0, -1, []],
            [6, 0, -1, []],
            [7, 0, -1, []],
            [8, 0, -1, []],
        ];

        foreach ($general as $lesson) {
            Course::create([
                "course_type_id" => 1, // General
                "equipped" => $lesson[1],
                "pre" => $lesson[2],
                "need" => $lesson[3],
            ]);
        }

        $basic = [
            [9, 0, -1, []],
            [10, 0, -1, []],
            [11, 0, 10, []],
            [12, 0, 11, []],
            [13, 0, 12, []],
            [14, 0, -1, []],
            [15, 0, 9, [14, 10]],
            [16, 0, -1, [14]],
            [17, 0, -1, []],
            [18, 0, -1, []],
            [19, 0, -1, []],
            [20, 0, -1, []],
        ];

        foreach ($basic as $lesson) {
            Course::create([
                "course_type_id" => 2, // Basic
                "equipped" => $lesson[1],
                "pre" => $lesson[2],
                "need" => $lesson[3],
            ]);
        }

        $main = [
            [21, 1, -1, []],
            [22, 1, -1, []],
            [23, 1, 22, []],
            [24, 1, 23, []],
            [25, 1, 24, []],
            [26, 1, -1, []],
            [27, 1, -1, []],
            [28, 1, -1, []],
            [29, 1, -1, []],
            [30, 1, -1, []],
            [31, 2, -1, []],
            [32, 2, -1, []],
            [33, 2, -1, []],
            [34, 2, -1, []],
            [35, 2, -1, []],
            [36, 2, -1, []],
            [37, 0, -1, []],
            [38, 0, -1, []],
            [39, 0, -1, []],
            [40, 0, -1, []],
            [41, 0, -1, []],
            [42, 0, -1, []],
            [43, 0, -1, []],
            [44, 0, -1, []],
            [45, 0, -1, []],
        ];

        foreach ($main as $lesson) {
            Course::create([
                "course_type_id" => 3, // Main
                "equipped" => $lesson[1],
                "pre" => $lesson[2],
                "need" => $lesson[3],
            ]);
        }

    }
}

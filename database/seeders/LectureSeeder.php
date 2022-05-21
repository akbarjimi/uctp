<?php

namespace Database\Seeders;

use App\Models\Lecture;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LectureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lectures = [
            [
                0, // id
                "استاد شماره 1", // name
                [
                    [
                        0, // day id
                        [0, 2] // hour id
                    ],
                    [
                        2, // day id
                        [0, 1, 5] // hour
                    ]
                ]
            ],
            [1, "استاد شماره 2", [[1, [3, 4, 7]], [2, [-1]]]],
            [2, "استاد شماره 3", [[-1, [-1]]]],
            [3, "استاد شماره 4", [[-1, [-1]]]],
            [4, "استاد شماره 5", [[-1, [-1]]]],
            [5, "استاد شماره 6", [[-1, [-1]]]],
            [6, "استاد شماره 7", [[1, [3]]]],
            [7, "استاد شماره 8", [[-1, [-1]]]],
            [8, "استاد شماره 9", [[-1, [-1]]]],
            [9, "استاد شماره 10", [[-1, [-1]]]],
            [10, "استاد شماره 11", [[-1, [-1]]]],
            [11, "استاد شماره 12", [[-1, [-1]]]],
            [12, "استاد شماره 13", [[-1, [-1]]]],
            [13, "استاد شماره 14", [[-1, [-1]]]],
            [15, "استاد شماره 15", [[-1, [-1]]]],
        ];

        foreach ($lectures as $lecture) {
            Lecture::create([
                "name" => $lecture[1],
                "data" => $lecture[2],
            ]);
        }

    }
}

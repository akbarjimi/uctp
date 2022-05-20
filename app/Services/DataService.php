<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseType;
use App\Models\DaysTime;
use App\Models\Room;
use App\Services\DataServiceInterface;

class DataService implements DataServiceInterface
{
    public function getTimeOfDay()
    {
        return [
            [0, 0, 0, 0],
            [1, 0, 0, 1],
            [2, 0, 0, 2],
            [3, 1, 0, 3],
            [4, 1, 0, 4],
            [5, 0, 1, 5],
            [6, 0, 1, 6],
            [7, 1, 1, 7],
            [8, 1, 1, 8]
        ];
    }

    public function getTimeTitles()
    {
        return [
            [0, ["8:00", "9:00"]],
            [1, ["9:30", "10:30"]],
            [2, ["11:00", "12:00"]],
            [3, ["7:30", "9:00"]],
            [4, ["9:30", "11:00"]],
            [5, ["13:00", "14:00"]],
            [6, ["14:30", "15:30"]],
            [7, ["13:30", "15:00"]],
            [8, ["15:30", "17:00"]],
        ];
    }
    public function getDaysTime()
    {
        return DaysTime::all()->map(function (DaysTime $daysTime) {
            return [
                $daysTime->name,
                $daysTime->hours,
                $daysTime->id-1,
            ];
        });
    }

    public function getClasses()
    {
        return Room::all()->map(function (Room $room) {
            return [
                $room->name,
                $room->equipped,
            ];
        })->toArray();
    }

    public function getLectures()
    {
        return [
            [
                0,
                "L1",
                [
                    [
                        0,
                        [0, 2]
                    ],
                    [
                        2,
                        [0, 1, 5]
                    ]
                ]
            ],

            [1, "L2", [[1, [3, 4, 7]], [2, [-1]]]],
            [2, "L3", [[-1, [-1]]]],
            [3, "L4", [[-1, [-1]]]],
            [4, "L5", [[-1, [-1]]]],
            [5, "L6", [[-1, [-1]]]],
            [6, "L7", [[1, [3]]]],
            [7, "L8", [[-1, [-1]]]],
            [8, "L9", [[-1, [-1]]]],
            [9, "L10", [[-1, [-1]]]],
            [10, "L11", [[-1, [-1]]]],
            [11, "L12", [[-1, [-1]]]],
            [12, "L13", [[-1, [-1]]]],
            [13, "L14", [[-1, [-1]]]],
            [15, "L15", [[-1, [-1]]]],
        ];
    }

    public function getProfessorsAvalibleLessons()
    {
        return [
            [0, [2], [21, 22, 23]],
            [3, [1], []],
            [1, [2], [21, 22, 23]],
            [5, [1, 2], []],
            [2, [1], []],
            [4, [0, 1], []],
        ];
    }

    public function getLessons()
    {
        return [
            [0, 0, "101", "DG1", 1],
            [1, 1, "102", "DG2", 1],
            [2, 2, "103", "DG3", 1],
            [3, 3, "104", "DG4", 1],
            [4, 4, "105", "DG5", 1],
            [5, 5, "106", "DG6", 1],
            [6, 6, "107", "DG7", 1],
            [7, 7, "108", "DG8", 1],
            [8, 8, "109", "DG9", 1],

            [9, 9, "201", "DP1", 1],
            [10, 10, "202", "DP2", 1],
            [11, 11, "203", "DP3", 1],
            [12, 12, "204", "DP4", 1],
            [13, 13, "205", "DP5", 1],
            [14, 14, "206", "DP6", 1],
            [15, 15, "207", "DP7", 1],
            [16, 16, "208", "DP8", 1],
            [17, 17, "209", "DP9", 1],
            [18, 18, "210", "DP10", 1],
            [19, 19, "211", "DP11", 1],
            [20, 20, "212", "DP12", 1],

            [21, 21, "301", "DS1", 2],
            [22, 22, "302", "DS2", 2],
            [23, 23, "303", "DS3", 2],
            [24, 24, "304", "DS4", 2],
            [25, 25, "305", "DS5", 2],
            [26, 26, "306", "DS6", 2],
            [27, 27, "307", "DS7", 2],
            [28, 28, "308", "DS8", 2],
            [29, 29, "309", "DS9", 2],
            [30, 30, "310", "DS10", 2],
            [31, 31, "311", "DS11", 2],
            [32, 32, "312", "DS12", 2],
            [33, 33, "313", "DS13", 2],
            [34, 34, "314", "DS14", 2],
            [35, 35, "315", "DS15", 2],
            [36, 36, "316", "DS16", 2],
            [37, 37, "317", "DS17", 2],
            [38, 38, "318", "DS18", 2],
            [39, 39, "319", "DS19", 2],
            [40, 40, "320", "DS20", 2],
            [41, 41, "321", "DS21", 2],
            [42, 42, "322", "DS22", 2],
            [43, 43, "323", "DS23", 2],
            [44, 44, "324", "DS24", 2],
            [45, 45, "325", "DS25", 2],

            [46, 45, "326", "DS25", 2],
            [47, 45, "327", "DS25", 3],
            [48, 45, "328", "DS25", 2],
            [49, 45, "329", "DS25", 2],
            [50, 44, "330", "DS24", 2],
            [51, 44, "331", "DS24", 2],
            [52, 44, "332", "DS24", 2],
            [53, 44, "333", "DS24", 2],
            [54, 44, "334", "DS24", 2],

        ];
    }

    public function getGeneralLessons()
    {
        return CourseType::whereName("general")->with("courses")->first()->courses->map(function (Course $course) {
            return [
                $course->id - 1,
                $course->equipped,
                $course->pre,
                $course->need,
            ];
        })->toArray();
    }

    public function getBasicLessons()
    {
        return CourseType::whereName("basic")->with("courses")->first()->courses->map(function (Course $course) {
            return [
                $course->id - 1,
                $course->equipped,
                $course->pre,
                $course->need,
            ];
        })->toArray();
    }

    public function getMainCourses()
    {
        return CourseType::whereName("main")->with("courses")->first()->courses->map(function (Course $course) {
            return [
                $course->id - 1,
                $course->equipped,
                $course->pre,
                $course->need,
            ];
        })->toArray();
    }

    public function getStudentGroups()
    {
        return [
            [0, "SY1"], [1, "SY2"], [2, "SY3"], [3, "SY4"],
        ];
    }

    public function getStudentGroupCourse()
    {
        return [
            [31, 0], [31, 1], [31, 2],
            [32, 0],
            [33, 1], [33, 2],
            [34, 1], [34, 2], [34, 3],
        ];
    }
}

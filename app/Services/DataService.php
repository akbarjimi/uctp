<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseType;
use App\Models\Days;
use App\Models\DaysTime;
use App\Models\Lecture;
use App\Models\Lesson;
use App\Models\Room;
use App\Models\StudentYear;
use App\Models\Time;
use App\Services\DataServiceInterface;

class DataService implements DataServiceInterface
{
    public function getTimeOfDay()
    {
        return Days::all()->map(function(Days $days) {
            return [
                $days->id - 1,
                $days->odd,
                $days->evening,
                $days->time,
            ];
        })->toArray();
    }

    public function getTimeTitles()
    {
        return Time::all()->map(function (Time $time) {
            return [
                $time->id - 1,
                [
                    $time->start,
                    $time->end,
                ]
                ];
        })->toArray();
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

    public function getLecturers()
    {
        return Lecture::all()->map(function (Lecture $lecture) {
            return [
                $lecture->id - 1,
                $lecture->name,
                $lecture->data,
            ];
        });
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
        return Lesson::all()->map(function (Lesson $lesson) {
            return [
                $lesson->id -1,
                $lesson->id -1,
                $lesson->lesson_code,
                $lesson->lesson_title,
                $lesson->lesson_count,
            ];
        });
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
        return StudentYear::all()->map(function (StudentYear $studentYear) {
            return [
                $studentYear->id - 1,
                $studentYear->name,
            ];
        })->toArray();
    }

    public function getStudentGroupCourse()
    {
        return [
            // groupStudentId - groupStudentTitle
            [31, 0],
            [31, 1],
            [31, 2],

            [32, 0],

            [33, 1],
            [33, 2],

            [34, 1],
            [34, 2],
            [34, 3],
        ];
    }
}

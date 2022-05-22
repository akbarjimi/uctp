<?php

namespace App\Services;

use App\Models\DaysTime;
use App\Models\Lecture;
use App\Services\DataServiceInterface;

class CspResolver implements CspResolverInterface
{
    public $data;

    public $engine;

    public function __construct(
        DataServiceInterface $data,
        CspEngineInterface $engine,
    ){
        $this->data = $data;
        $this->engine = $engine;
    }

    public function resolve()
    {
        $errorArray = [];
        // when school is open
        $daysTime = $this->data->getDaysTime();

        $classes = $this->data->getClasses();

        // $professors = $this->data->getLectures();
        $lecturers = $this->data->getLecturers();

        $lessons = $this->data->getLessons();
        $general_lessons = $this->data->getGeneralLessons();
        $basic_lessons = $this->data->getBasicLessons();
        $main_courses = $this->data->getMainCourses();
        $times_of_day = $this->data->getTimeOfDay();
        $student_group = $this->data->getStudentGroups();
        $student_group_course = $this->data->getStudentGroupCourse();
        $professors_avalible_lessons = $this->data->getProfessorsAvalibleLessons();

        $TotalClassInWeek = count($classes) * $daysTime->reduce(function ($result, $day) {
            return count($day[1]);
        });

        $TotalCourseNo = $lessons->reduce(function ($result, $lesson) {
            return $lesson[4];
        });

        $lessonsListArray = [$general_lessons, $basic_lessons, $main_courses];

        if ($TotalCourseNo > $TotalClassInWeek) {
            array_push($errorArray, \trans("messages.over"));
        }

        if (count($lessons) > $lecturers->count()) {
            array_push($errorArray, \trans("messages.not_enough"));
        }

        $finalResponse = $this->engine->mix(
            $daysTime,
            $lessons,
            $lecturers,
            $classes,
            $professors_avalible_lessons,
            $lessonsListArray,
            $errorArray
        );

        $errorArray = $finalResponse[1];

        return [$finalResponse, $errorArray];
    }
}

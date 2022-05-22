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

        $hosh_classes = $this->data->getClasses();

        // $hosh_professors = $this->data->getLectures();
        $lecturers = $this->data->getLecturers();

        $hosh_lessons = $this->data->getLessons();
        $hosh_general_lessons = $this->data->getGeneralLessons();
        $hosh_basic_lessons = $this->data->getBasicLessons();
        $hosh_main_courses = $this->data->getMainCourses();
        $hosh_times_of_day = $this->data->getTimeOfDay();
        $hosh_student_group = $this->data->getStudentGroups();
        $hosh_student_group_course = $this->data->getStudentGroupCourse();
        $hosh_professors_avalible_lessons = $this->data->getProfessorsAvalibleLessons();

        $TotalClassInWeek = count($hosh_classes) * $daysTime->reduce(function ($result, $day) {
            return count($day[1]);
        });

        $TotalCourseNo = $hosh_lessons->reduce(function ($result, $lesson) {
            return $lesson[4];
        });

        $lessonsListArray = [$hosh_general_lessons, $hosh_basic_lessons, $hosh_main_courses];

        if ($TotalCourseNo > $TotalClassInWeek) {
            array_push($errorArray, \trans("messages.over"));
        }

        if (count($hosh_lessons) > $lecturers->count()) {
            array_push($errorArray, \trans("messages.not_enough"));
        }

        $finalResponse = $this->engine->matchInfoTogether(
            $daysTime,
            $hosh_lessons,
            $lecturers,
            $hosh_student_group,
            $hosh_student_group_course,
            $hosh_times_of_day,
            $hosh_classes,
            $hosh_professors_avalible_lessons,
            $lessonsListArray,
            $errorArray
        );

        $errorArray = $finalResponse[1];

        return [$finalResponse, $errorArray];
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\CspResolverInterface;
use App\Services\DataServiceInterface;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function show(
        Request $request,
        DataServiceInterface $data,
        CspResolverInterface $resolver,
        string $id,
        string $title,
    ) {
        [$finalResponse, $errorArray] = $resolver->calculate();

        return view("course", [
            'finalResponse' => $finalResponse,
            'errorArray' => $errorArray,
            'messageArray' => $resolver->messages(),
            'groupStudentId' => $id,
            'groupStudentTitle' => $title,

            'hosh_times_of_day' => $data->getTimeOfDay(),
            'hosh_time_titles' => $data->getTimeTitles(),
            'hosh_daysTime' => $data->getDaysTime(),
            'hosh_classes' => $data->getClasses(),
            'hosh_professors' => $data->getLectures(),
            'hosh_lessons' => $data->getLessons(),
            'hosh_professors_avalible_lessons' => $data->getProfessorsAvalibleLessons(),
            'hosh_general_lessons' => $data->getGeneralLessons(),
            'hosh_basic_lessons' => $data->getBasicLessons(),
            'hosh_main_courses' => $data->getMainCourses(),
            'hosh_student_group' => $data->getStudentGroups(),
            'hosh_student_group_course' => $data->getStudentGroupCourse(),
        ]);
    }
}

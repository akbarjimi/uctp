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
        [$finalResponse, $errorArray] = $resolver->resolve();

        return view("course", [
            'finalResponse' => $finalResponse,
            'errorArray' => $errorArray,
            'messageArray' => $this->messages(),
            'groupStudentId' => $id,
            'groupStudentTitle' => $title,

            'times_of_day' => $data->getTimeOfDay(),
            'time_titles' => $data->getTimeTitles(),
            'daysTime' => $data->getDaysTime(),
            'classes' => $data->getClasses(),
            'professors' => $data->getLecturers(),
            'lessons' => $data->getLessons(),
            'professors_avalible_lessons' => $data->getProfessorsAvalibleLessons(),
            'general_lessons' => $data->getGeneralLessons(),
            'basic_lessons' => $data->getBasicLessons(),
            'main_courses' => $data->getMainCourses(),
            'student_group' => $data->getStudentGroups(),
            'student_group_course' => $data->getStudentGroupCourse(),
        ]);
    }
}

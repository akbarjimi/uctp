<?php

namespace App\Http\Controllers;


use App\Services\CspResolverInterface;
use App\Services\DataServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class RoomController extends Controller
{
    public function show(
        Request $request,
        DataServiceInterface $data,
        CspResolverInterface $resolver,
        string $room
    ){
        [$finalResponse, $errorArray] = $resolver->calculate();

        $key = 0;
        foreach ($finalResponse[0][0]["schedules"] as $index => $value) {
            if ($value["info"][0] === $room) {
                $key = $index;
            }
        }

        $class_info = $finalResponse[0][0]["schedules"][$key];
        $classId = $key;

        return view("room", [
            'finalResponse' => $finalResponse,
            'errorArray' => $errorArray,
            'messageArray' => $resolver->messages(),
            'class_info' => $class_info,
            'classId' => $classId,

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

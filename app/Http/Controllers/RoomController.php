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
        [$finalResponse, $errorArray] = $resolver->resolve();

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
            'messageArray' => $this->messages(),
            'class_info' => $class_info,
            'classId' => $classId,

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

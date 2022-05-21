<?php

namespace App\Http\Controllers;

use App\Services\CspResolverInterface;
use App\Services\DataServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class LectureController extends Controller
{
    public function show(
        Request $request,
        DataServiceInterface $data,
        CspResolverInterface $resolver,
        string $lecture
    ) {
        [$finalResponse, $errorArray] = $resolver->calculate();
        $hosh_professors = $data->getLectures();

        $prof_list = $finalResponse[0][0]["teachers_schedules"];
        foreach ($prof_list as $prof_info) {
            $prof_id = $prof_info[0]; // id ostad
            if ($prof_id == $lecture) {
                $prof_name = "";
                foreach ($hosh_professors as $all_prof_info) {
                    if ($prof_id == $all_prof_info[0]) {
                        $prof_name = $all_prof_info[1];
                        break;
                    }
                }
                break;
            }
        }

        return view("lecture", [
            'finalResponse' => $finalResponse,
            'errorArray' => $errorArray,
            'messageArray' => $resolver->messages(),
            'prof_name' => $prof_name,
            'prof_id' => $prof_id,
            'prof_info' => $prof_info,

            'hosh_times_of_day' => $data->getTimeOfDay(),
            'hosh_time_titles' => $data->getTimeTitles(),
            'hosh_daysTime' => $data->getDaysTime(),
            'hosh_classes' => $data->getClasses(),
            'hosh_professors' => $hosh_professors,
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

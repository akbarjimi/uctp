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
        [$finalResponse, $errorArray] = $resolver->resolve();
        $professors = $data->getLecturers();

        $prof_list = $finalResponse[0][0]["teachers_schedules"];
        foreach ($prof_list as $prof_info) {
            $prof_id = $prof_info[0]; // id ostad
            if ($prof_id == $lecture) {
                $prof_name = "";
                foreach ($professors as $all_prof_info) {
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
            'messageArray' => $this->messages(),
            'prof_name' => $prof_name,
            'prof_id' => $prof_id,
            'prof_info' => $prof_info,

            'times_of_day' => $data->getTimeOfDay(),
            'time_titles' => $data->getTimeTitles(),
            'daysTime' => $data->getDaysTime(),
            'classes' => $data->getClasses(),
            'professors' => $professors,
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

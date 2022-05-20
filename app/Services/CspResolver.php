<?php

namespace App\Services;

use App\Services\DataServiceInterface;

class CspResolver implements CspResolverInterface
{
    public $data;

    public function __construct(DataServiceInterface $data)
    {
        $this->data = $data;
    }

    public function messages(?int $index = null)
    {
        $messages = [
            "تعداد دروس ارائه شده از ظرفیت کلاس ها بیشتر می باشد",
            "همه چیز عالی پیشرفت",
            "تعداد اساتید برای ارائه این دروس کافی نمی باشد",
            "برای کد درسی روبرو هیچ استادی یافت نشده است :‌",
            ""
        ];

        if ($index === null) {
            return $messages;
        } else {
            return $messages[$index];
        }
    }

    public function calculate()
    {
        $errorArray = array();

        $hosh_daysTime = $this->data->getDaysTime();
        $hosh_classes = $this->data->getClasses();
        $hosh_professors = $this->data->getLectures();
        $hosh_lessons = $this->data->getLessons();
        $hosh_general_lessons = $this->data->getGeneralLessons();
        $hosh_basic_lessons = $this->data->getBasicLessons();
        $hosh_main_courses = $this->data->getMainCourses();
        $hosh_times_of_day = $this->data->getTimeOfDay();
        $hosh_student_group = $this->data->getStudentGroups();
        $hosh_student_group_course = $this->data->getStudentGroupCourse();
        $hosh_professors_avalible_lessons = $this->data->getProfessorsAvalibleLessons();

        $daysNo = count($hosh_daysTime);
        $hoursOfClass = 0;
        for ($i = 0; $i < $daysNo; $i++) {
            $hoursOfClass += count($hosh_daysTime[$i][1]);
        }
        $TotalClassInWeek = $hoursOfClass * count($hosh_classes);

        $TotalCourseNo = 0;
        for ($i = 0; $i < count($hosh_lessons); $i++) {
            $TotalCourseNo += $hosh_lessons[$i][4];
        }

        $totalProfessorsCanTeach = count($hosh_professors);
        $totalHoursHaveProfessors = 0;
        $totalHoursHaveProfessors = $this->findNumberHoursProfessorsCanTeachInTotal($totalHoursHaveProfessors, $hosh_professors, $daysNo);

        $lessonsListArray = [$hosh_general_lessons, $hosh_basic_lessons, $hosh_main_courses];

        if ($TotalCourseNo > $TotalClassInWeek) {
            array_push($errorArray, $this->messages(0));
        }

        if (count($hosh_lessons) > $hosh_professors) {
            array_push($errorArray, $this->messages(2));
        }

        $finalResponse = $this->matchInfoTogether($hosh_daysTime, $hosh_lessons, $hosh_professors, $hosh_student_group, $hosh_student_group_course, $hosh_times_of_day, $hosh_classes, $hosh_professors_avalible_lessons, $lessonsListArray, $errorArray);
        $errorArray = $finalResponse[1];

        return [$finalResponse, $errorArray];
    }

    public function matchInfoTogether($hosh_daysTime, $hosh_lessons, $hosh_professors, $hosh_student_group, $hosh_student_group_course, $hosh_times_of_day, $hosh_classes, $hosh_professors_avalible_lessons, $lessonsListArray, $errorArray)
    {
        $tmpHosh_lessons = $hosh_lessons;
        $tmpHosh_professors = $hosh_professors;
        $matrixOrgLessons = $this->createEmptyMatrix($hosh_daysTime);
        $ArrayWeeklyClassSchedules = array();
        for ($k = 0; $k < count($hosh_classes); $k++) {
            $classInfo['info'] = $hosh_classes[$k];
            $classInfo['weekSchedule'] = $matrixOrgLessons;
            array_push($ArrayWeeklyClassSchedules, $classInfo);
        }
        $propDoWorkMatrix = array();
        $returnArray = array();
        foreach ($hosh_lessons as $dars) {
            $rowInfo = array();
            $idDars = $dars[0];
            $findProfessorId = -1;
            $groupId = $this->findLessonsGroupId($idDars, $lessonsListArray);
            $rowInfo['dars_id'] = $idDars;
            $rowInfo['group_id'] = $groupId;
            $ArrayListOfAllProfCanTeach = $this->findAllProfessorsForOneCourse($hosh_professors_avalible_lessons, $hosh_professors, $idDars, $groupId, $hosh_professors_avalible_lessons, $hosh_professors);
            $rowInfo['profs_array'] = $ArrayListOfAllProfCanTeach;
            $response_of_prof_finder = $this->findProfessorForCourse($tmpHosh_professors, $ArrayListOfAllProfCanTeach, $ArrayWeeklyClassSchedules, $propDoWorkMatrix, $dars);
            $findOneTeacher = $response_of_prof_finder[0];
            if ($findOneTeacher > -1) {
                $propDoWorkMatrix = $response_of_prof_finder[1];
                $ArrayWeeklyClassSchedules = $response_of_prof_finder[2];
                $rowInfo['prof_id'] = $findOneTeacher;
                array_push($returnArray, $rowInfo);
            } else {
                array_push($errorArray, $this->messages(3) . $idDars);
            }
        }
        $returnArrayResopnse = array();
        $rowInfoTotal = array();
        $rowInfoTotal['info'] = $returnArray;
        $rowInfoTotal['schedules'] = $ArrayWeeklyClassSchedules;
        $rowInfoTotal['teachers_schedules'] = $propDoWorkMatrix;
        array_push($returnArrayResopnse, $rowInfoTotal);
        return [$returnArrayResopnse, $errorArray];
    }

    public function findProfessorForCourse($tmpHosh_professors, $ArrayListOfAllProfCanTeach, $ArrayWeeklyClassSchedules, $propDoWorkMatrix, $classInfo)
    {
        $classId = $classInfo[0];
        $classPartsNo = $classInfo[4];
        $findOneTeacher = -1;
        foreach ($ArrayListOfAllProfCanTeach as $profId) {
            if ($findOneTeacher > -1) {
                break;
            }
            foreach ($tmpHosh_professors as $profLines) {
                if ($findOneTeacher > -1) {
                    break;
                }
                if ($profLines[0] == $profId) {

                    if ($classPartsNo < 1) {
                        $classPartsNo = 1;
                    }

                    $findOneTeacherTmp = -1;
                    $loopController = 0;
                    $ArrayWeeklyClassSchedulesTmp = $ArrayWeeklyClassSchedules;
                    $propDoWorkMatrixTmp = $propDoWorkMatrix;

                    for ($countI = 0; $countI < $classPartsNo; $countI++) {
                        $findOneTeacherTmp = -1;
                        $k = -1;
                        foreach ($ArrayWeeklyClassSchedulesTmp as $ClassSchedules) {
                            if ($findOneTeacher > -1) {
                                break;
                            }
                            $daysId = -1;
                            $k++;

                            foreach ($ClassSchedules['weekSchedule'] as $daysScheules) {
                                if ($findOneTeacherTmp > -1) {
                                    break;
                                }
                                $daysId++;
                                $hourId = -1;
                                foreach ($daysScheules as $hourScheules) {
                                    if ($findOneTeacherTmp > -1) {
                                        break;
                                    }
                                    $hourId++;

                                    if ($hourScheules == -1) {
                                        $retValue = $this->checkProfAvailable($profId, $tmpHosh_professors, $propDoWorkMatrixTmp, $hourId, $daysId, $classId, $k);
                                        $findOneTeacherTmp = $retValue[0];
                                        if ($findOneTeacherTmp > -1) {
                                            $propDoWorkMatrixTmp = $retValue[1];
                                            $loopController++;
                                            $ArrayWeeklyClassSchedulesTmp[$k]['weekSchedule'][$daysId][$hourId] = $findOneTeacherTmp;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if ($findOneTeacherTmp > -1 && $loopController == $classPartsNo) {
                        $findOneTeacher = $findOneTeacherTmp;
                        $ArrayWeeklyClassSchedules = $ArrayWeeklyClassSchedulesTmp;
                        $propDoWorkMatrix = $propDoWorkMatrixTmp;
                    }
                }
            }
        }
        return [$findOneTeacher, $propDoWorkMatrix, $ArrayWeeklyClassSchedules];
    }

    public function checkProfAvailable($profId, $tmpHosh_professors, $profListMatrix, $hourId, $dayId, $courseId, $classId)
    {
        $findProf = 0;
        $canAddThisClassForProf = -1;
        $loopNo = -1;
        foreach ($profListMatrix as $rowInfo) {
            $loopNo++;
            if ($findProf == 1)
                break;
            if ($rowInfo[0] == $profId) {
                $findDayAndHour = 0;
                $findProf = 1;
                $findOtherClass = 0;


                // گشتن دنبال اینکه آیا این استاد درس دیگری در این تایم دارد که تدریس کند یا خیر
                foreach ($rowInfo[1] as $dayAndHour) {
                    if ($findOtherClass == 1)
                        break;
                    if ($dayAndHour[0] == $dayId && $dayAndHour[1] == $hourId) {
                        $findOtherClass = 1;
                        //echo "1 : dayAndHour[0] : ".$dayAndHour[0]." ,dayId : $dayId ,dayAndHour[1] : ".$dayAndHour[1]." ,hourId : $hourId <br>";
                    } else {
                        //echo "0 : dayAndHour[0] : ".$dayAndHour[0]." ,dayId : $dayId ,dayAndHour[1] : ".$dayAndHour[1]." ,hourId : $hourId <br>";
                    }
                }

                // چک کردن اینکه اصلا آیا استاد در این تاریخ و ساعت در مرکز آموزش هست یا خیر
                if ($findOtherClass == 0) {
                    $canTeachInThisDayAndHour = 0;
                    foreach ($tmpHosh_professors as $tmp_professors) {
                        if ($tmp_professors[0] == $profId) {
                            foreach ($tmp_professors[2] as $availableDays) {
                                if ($availableDays[0] == -1 || $availableDays[0] == $dayId) {
                                    foreach ($availableDays[1] as $availableHours) {
                                        if ($availableHours == -1 || $availableHours == $hourId) {
                                            $canTeachInThisDayAndHour = 1;
                                            break;
                                        }
                                    }
                                    break;
                                }
                            }
                            break;
                        }
                    }
                    if ($canTeachInThisDayAndHour == 0) {
                        $findOtherClass = 1;
                    }
                }

                // در صورتی که این تایم برای استاد خالی است
                if ($findOtherClass == 0) {
                    $canAddThisClassForProf = $profId;
                    // افزودن یک زمان به ماتریس مربوط به اساتید
                    /* ********** */
                    $infoClassRow = array();
                    array_push($infoClassRow, $dayId); // روز تشکیل کلاس
                    array_push($infoClassRow, $hourId); // ساعت تشکیل کلاس
                    array_push($infoClassRow, $courseId); // آیدی درس
                    array_push($infoClassRow, $classId); // آیدی کلاس درس
                    array_push($rowInfo[1], $infoClassRow);

                    $newRowInfo = array();
                    array_push($newRowInfo, $rowInfo[0]);
                    array_push($newRowInfo, $rowInfo[1]);
                    $profListMatrix[$loopNo] = $newRowInfo;
                    /* $infoClassRow2 = array();
                    array_push($infoClassRow2,$infoClassRow);
                    array_push($rowInfo[1],$infoClassRow2); */
                }
            }
        }
        if ($findProf == 0) {
            // چک کردن اینکه استاد در این روز و ساعت در مرکز آموزش هست یا نیست
            $canTeachInThisDayAndHour = 0;
            foreach ($tmpHosh_professors as $tmp_professors) {
                if ($tmp_professors[0] == $profId) {
                    foreach ($tmp_professors[2] as $availableDays) {
                        if ($availableDays[0] == -1 || $availableDays[0] == $dayId) {
                            foreach ($availableDays[1] as $availableHours) {
                                if ($availableHours == -1 || $availableHours == $hourId) {
                                    $canTeachInThisDayAndHour = 1;
                                    break;
                                }
                            }
                            break;
                        }
                    }
                    break;
                }
            }
            if ($canTeachInThisDayAndHour == 1) {
                $canAddThisClassForProf = $profId;
                // افزودن یک استاد به همراه زمانش به ماتریس مربوط به اساتید
                /* ********** */
                $infoClassRow = array();
                $infoClassRow3 = array();
                array_push($infoClassRow, $dayId); // روز تشکیل کلاس
                array_push($infoClassRow, $hourId); // ساعت تشکیل کلاس
                array_push($infoClassRow, $courseId); // آیدی درس
                array_push($infoClassRow, $classId); // آیدی کلاس درس
                array_push($infoClassRow3, $infoClassRow);
                $infoClassRow2 = array();
                array_push($infoClassRow2, $profId);
                array_push($infoClassRow2, $infoClassRow3);
                array_push($profListMatrix, $infoClassRow2);
            }
        }

        return [$canAddThisClassForProf, $profListMatrix];
    }

    public function findLessonsGroupId($lessonsId, $lessonsListArray)
    {
        $retVal = -1;
        $row = 0;
        foreach ($lessonsListArray as $darsArray) {
            $row++;
            if ($retVal != -1)
                break;
            foreach ($darsArray as $darsArrayItems) {
                if ($retVal != -1)
                    break;
                foreach ($darsArrayItems as $lessons) {
                    if ($lessonsId == $lessons) {
                        $retVal = $row;
                        break;
                    }
                }
            }
        }
        return $retVal;
    }

    public function findAllProfessorsForOneCourse($hosh_professors_avalible_lessons, $hosh_professors, $lessonsId, $groupId)
    {
        //echo " [$lessonsId, $groupId] <br> ";
        $retArray = array();
        if ($lessonsId > -1) {
            foreach ($hosh_professors as $professor) {
                $canAdd = 0;
                $findProf = 0;
                foreach ($hosh_professors_avalible_lessons as $professorCanTeach) {
                    if ($findProf == 0) {
                        if ($professorCanTeach[0] == $professor[0]) {
                            $findProf = 1;
                            foreach ($professorCanTeach[2] as $item) {
                                if ($item == $lessonsId) {
                                    $canAdd = 1;
                                }
                            }
                        }
                    }
                }

                if (($canAdd == 1 || $findProf == 0) && !in_array($professor[0], $retArray))
                    array_push($retArray, $professor[0]);
            }
            if ($groupId > -1) {
                foreach ($hosh_professors as $professor) {
                    $canAdd = 0;
                    $findProf = 0;
                    foreach ($hosh_professors_avalible_lessons as $professorCanTeach) {
                        if ($findProf == 0) {
                            if ($professorCanTeach[0] == $professor[0]) {
                                $findProf = 1;
                                foreach ($professorCanTeach[1] as $item) {
                                    if ($item == $groupId) {
                                        $canAdd = 1;
                                    }
                                }
                            }
                        }
                    }
                    if (($canAdd == 1 || $findProf == 0) && !in_array($professor[0], $retArray))
                        array_push($retArray, $professor[0]);
                }
            }
        } else if ($groupId > -1) {
            foreach ($hosh_professors as $professor) {
                $canAdd = 0;
                $findProf = 0;
                foreach ($hosh_professors_avalible_lessons as $professorCanTeach) {
                    if ($findProf == 0) {
                        if ($professorCanTeach[0] == $professor[0]) {
                            $findProf = 1;
                            foreach ($professorCanTeach[1] as $item) {
                                if ($item == $groupId) {
                                    $canAdd = 1;
                                }
                            }
                        }
                    }
                }
                if (($canAdd == 1 || $findProf == 0) && !in_array($professor[0], $retArray))
                    array_push($retArray, $professor[0]);
            }
        } else {
            foreach ($hosh_professors as $professor) {
                if (!in_array($professor[0], $retArray))
                    array_push($retArray, $professor[0]);
            }
        }
        return $retArray;
    }

    public function findNumberHoursProfessorsCanTeachInTotal($totalHoursHaveProfessors, $hosh_professors, $daysNo)
    {
        $totalProfessorsCanTeach = count($hosh_professors);
        $totalHoursHaveProfessors = 0;
        for ($i = 0; $i < $totalProfessorsCanTeach; $i++) {
            $itemArray = $hosh_professors[$i][2];
            $checkAll = false;
            $countNoHour = 0;
            $AvrageHoursNo = 0;
            foreach ($itemArray as $item) {
                if (!$checkAll) {
                    if ($item[0] == -1) {
                        $checkAll = true;
                        $AvrageHoursNo = count($item[1]);
                    }
                }
            }
            if ($AvrageHoursNo < 1) {
                $AvrageHoursNo = 1;
            }
            if ($checkAll) {
                $countNoHour = $daysNo * $AvrageHoursNo;
            }
            foreach ($itemArray as $item) {
                if (!$checkAll) {
                    $hour = count($item[1]);
                    $countNoHour += $hour;
                } else {
                    if (count($item[1]) < $AvrageHoursNo) {
                        $countNoHour -= ($AvrageHoursNo - count($item[1]));
                    } else if (count($item[1]) < $AvrageHoursNo) {
                        $countNoHour += (count($item[1]) - $AvrageHoursNo);
                    }
                }
            }

            $totalHoursHaveProfessors += $countNoHour;
        }
        return $totalHoursHaveProfessors;
    }

    public function createEmptyMatrix($days)
    {
        $rowNo = count($days);
        $matrix = array();
        $colNo = 0;
        for ($i = 0; $i < $rowNo; $i++) {
            if ($colNo < count($days[$i][1])) {
                $colNo = count($days[$i][1]);
            }
        }
        for ($i = 0; $i < $rowNo; $i++) {
            $newRow = array();
            for ($j = 0; $j < $colNo; $j++) {
                array_push($newRow, -1);
            }
            array_push($matrix, $newRow);
        }
        // اعمال محدودیت روی روز هایی که ساعات فعال کمتری دارند
        for ($i = 0; $i < $rowNo; $i++) {
            for ($j = 0; $j < $colNo; $j++) {
                if ($j > count($days[$i][1]) - 1) {
                    $matrix[$i][$j] = -2;
                }
            }
        }
        return $matrix;
    }

    public function printOneMatrix($matrix)
    {
        $rowNo = count($matrix);
        $varText = "[ ";
        for ($i = 0; $i < $rowNo; $i++) {
            $line = "&nbsp;&nbsp;&nbsp;";
            for ($j = 0; $j < count($matrix[$i]); $j++) {
                if ($j + 1 == count($matrix[$i])) {
                    $line .=  $matrix[$i][$j];
                } else {
                    $line .=  $matrix[$i][$j] . ", &nbsp;";
                }
            }
            $varText .= "<br>" . $line;
        }
        return $varText . " <br> ]";
    }

    public function showJasonArrayBeauty($JasonText)
    {
        $JasonText = trim(preg_replace('/\s\s+/', ' ', $JasonText));
        $JasonText = str_replace('},', '},<br>&nbsp&nbsp', $JasonText);
        $JasonText = str_replace(' [ { ', '[ <br>&nbsp {', $JasonText);
        //$JasonText = str_replace(' ] }', '<br>&nbsp}<br>}', $JasonText);
        $JasonText = str_replace('], [', '],<br>&nbsp&nbsp[', $JasonText);
        $JasonText = str_replace('] ],', '] <br>&nbsp],', $JasonText);
        $JasonText = str_replace('[ [', '[ <br>&nbsp&nbsp[', $JasonText);
        $JasonText = str_replace('} ]', '} <br>&nbsp]', $JasonText);
        $JasonText = str_replace('] ]', '] <br>&nbsp]', $JasonText);
        return $JasonText;
    }
}

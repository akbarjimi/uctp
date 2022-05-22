<?php

namespace App\Services;

class CspEngine implements CspEngineInterface
{
    public function mix($daysTime, $lessons, $professors, $classes, $professors_avalible_lessons, $lessonsListArray, $errorArray)
    {
        $tmpHosh_professors = $professors;
        $matrixOrgLessons = $this->getMap($daysTime);
        $ArrayWeeklyClassSchedules = [];
        for ($k = 0; $k < count($classes); $k++) {
            $classInfo['info'] = $classes[$k];
            $classInfo['weekSchedule'] = $matrixOrgLessons;
            array_push($ArrayWeeklyClassSchedules, $classInfo);
        }
        $propDoWorkMatrix = [];
        $returnArray = [];
        foreach ($lessons as $dars) {
            $rowInfo = [];
            $idDars = $dars[0];
            $groupId = $this->getGroupId($idDars, $lessonsListArray);
            $rowInfo['dars_id'] = $idDars;
            $rowInfo['group_id'] = $groupId;
            $ArrayListOfAllProfCanTeach = $this->getCourseLecturers($professors_avalible_lessons, $professors, $idDars, $groupId, $professors_avalible_lessons, $professors);
            $rowInfo['profs_array'] = $ArrayListOfAllProfCanTeach;
            $response_of_prof_finder = $this->finder($tmpHosh_professors, $ArrayListOfAllProfCanTeach, $ArrayWeeklyClassSchedules, $propDoWorkMatrix, $dars);
            $findOneTeacher = $response_of_prof_finder[0];
            if ($findOneTeacher > -1) {
                $propDoWorkMatrix = $response_of_prof_finder[1];
                $ArrayWeeklyClassSchedules = $response_of_prof_finder[2];
                $rowInfo['prof_id'] = $findOneTeacher;
                array_push($returnArray, $rowInfo);
            } else {
                array_push($errorArray, \trans("messages.not_found", [$idDars]));
            }
        }
        $returnArrayResopnse = [];
        $rowInfoTotal = [];
        $rowInfoTotal['info'] = $returnArray;
        $rowInfoTotal['schedules'] = $ArrayWeeklyClassSchedules;
        $rowInfoTotal['teachers_schedules'] = $propDoWorkMatrix;
        array_push($returnArrayResopnse, $rowInfoTotal);
        return [$returnArrayResopnse, $errorArray];
    }

    public function finder($tmpHosh_professors, $ArrayListOfAllProfCanTeach, $ArrayWeeklyClassSchedules, $propDoWorkMatrix, $classInfo)
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
                                        $retValue = $this->isAvailable($profId, $tmpHosh_professors, $propDoWorkMatrixTmp, $hourId, $daysId, $classId, $k);
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

    public function isAvailable($profId, $tmpHosh_professors, $profListMatrix, $hourId, $dayId, $courseId, $classId)
    {
        $findProf = 0;
        $canAddThisClassForProf = -1;
        $loopNo = -1;
        foreach ($profListMatrix as $rowInfo) {
            $loopNo++;
            if ($findProf == 1)
                break;
            if ($rowInfo[0] == $profId) {
                $findProf = 1;
                $findOtherClass = 0;


                foreach ($rowInfo[1] as $dayAndHour) {
                    if ($findOtherClass == 1)
                        break;
                    if ($dayAndHour[0] == $dayId && $dayAndHour[1] == $hourId) {
                        $findOtherClass = 1;
                    }
                }

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

                if ($findOtherClass == 0) {
                    $canAddThisClassForProf = $profId;
                    $infoClassRow = [];
                    array_push($infoClassRow, $dayId); // روز تشکیل کلاس
                    array_push($infoClassRow, $hourId); // ساعت تشکیل کلاس
                    array_push($infoClassRow, $courseId); // آیدی درس
                    array_push($infoClassRow, $classId); // آیدی کلاس درس
                    array_push($rowInfo[1], $infoClassRow);

                    $newRowInfo = [];
                    array_push($newRowInfo, $rowInfo[0]);
                    array_push($newRowInfo, $rowInfo[1]);
                    $profListMatrix[$loopNo] = $newRowInfo;
                }
            }
        }
        if ($findProf == 0) {
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
                $infoClassRow = [];
                $infoClassRow3 = [];
                array_push($infoClassRow, $dayId); // روز تشکیل کلاس
                array_push($infoClassRow, $hourId); // ساعت تشکیل کلاس
                array_push($infoClassRow, $courseId); // آیدی درس
                array_push($infoClassRow, $classId); // آیدی کلاس درس
                array_push($infoClassRow3, $infoClassRow);
                $infoClassRow2 = [];
                array_push($infoClassRow2, $profId);
                array_push($infoClassRow2, $infoClassRow3);
                array_push($profListMatrix, $infoClassRow2);
            }
        }

        return [$canAddThisClassForProf, $profListMatrix];
    }

    public function getGroupId($lessonsId, $lessonsListArray)
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

    public function getCourseLecturers($professors_avalible_lessons, $professors, $lessonsId, $groupId)
    {
        $retArray = [];
        if ($lessonsId > -1) {
            foreach ($professors as $professor) {
                $canAdd = 0;
                $findProf = 0;
                foreach ($professors_avalible_lessons as $professorCanTeach) {
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
                foreach ($professors as $professor) {
                    $canAdd = 0;
                    $findProf = 0;
                    foreach ($professors_avalible_lessons as $professorCanTeach) {
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
            foreach ($professors as $professor) {
                $canAdd = 0;
                $findProf = 0;
                foreach ($professors_avalible_lessons as $professorCanTeach) {
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
            foreach ($professors as $professor) {
                if (!in_array($professor[0], $retArray))
                    array_push($retArray, $professor[0]);
            }
        }
        return $retArray;
    }

    public function getMap($days)
    {
        $rowNo = count($days);
        $matrix = [];
        $colNo = 0;
        for ($i = 0; $i < $rowNo; $i++) {
            if ($colNo < count($days[$i][1])) {
                $colNo = count($days[$i][1]);
            }
        }
        for ($i = 0; $i < $rowNo; $i++) {
            $newRow = [];
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
}

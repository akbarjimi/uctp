<?php 
    $errorArray = array();
    $messageArray = [
        "تعداد دروس ارائه شده از ظرفیت کلاس ها بیشتر می باشد",
        "همه چیز عالی پیشرفت",
        "تعداد اساتید برای ارائه این دروس کافی نمی باشد",
        "برای کد درسی روبرو هیچ استادی یافت نشده است :‌",
        ""
    ];

    $daysNo = count($hosh_daysTime) ; // تعداد روز های هفته
    $hoursOfClass = 0 ; // تعداد ساعاتی که در آنها می توان کلاس برگزار نمود
    for ($i=0; $i < $daysNo ; $i++) { 
        $hoursOfClass += count($hosh_daysTime[$i][1]);
    }
    //echo "daysNo : $daysNo  , hoursOfClass : $hoursOfClass";

    $TotalClassInWeek = $hoursOfClass * count($hosh_classes); // تعداد کل کلاس هایی که می توان در طول یک هفته برگزار شوند
    
    $TotalCourseNo = 0 ; // تعداد کل دروسی که باید در هفته برگزار شوند
    for ($i=0; $i < count($hosh_lessons) ; $i++) { 
        $TotalCourseNo += $hosh_lessons[$i][4];
    }

    $totalProfessorsCanTeach = count($hosh_professors); // تعداد اساتید که قرار است درس ارائه کنند
    $totalHoursHaveProfessors = 0; // تعداد ساعات درسی که اساتید در مجموع می توانند درس ارائه نمایند
    $totalHoursHaveProfessors = findNumberHoursProfessorsCanTeachInTotal($totalHoursHaveProfessors, $hosh_professors, $daysNo);
    
    $lessonsListArray = [ $hosh_general_lessons, $hosh_basic_lessons, $hosh_main_courses ]; // لیست تمام گروه های درسی

    /* چک کردن شروط لازم */
    /* echo ("TotalCourseNo : $TotalCourseNo  , TotalClassInWeek : $TotalClassInWeek , count(hosh_lessons) : ".
        count($hosh_lessons)." , totalHoursHaveProfessors : $totalHoursHaveProfessors , hosh_professors : ".
        count($hosh_professors)." <br>"); */

    // بررسی اینکه آیا تعداد کلاس ها برای تعداد دروس ارائه شده کافی است یا خیر ؟
    if($TotalCourseNo > $TotalClassInWeek){
        array_push($errorArray,$messageArray[0]);
    } 

    // بررسی اینکه اساتید کافی برای تدریس دروس وجود دارد یا خیر
    if(count($hosh_lessons) > $hosh_professors){
        array_push($errorArray,$messageArray[2]);
    } 



    /* ایجاد یک جدول اولیه براساس شروط لازم */
    $finalResponse = matchInfoTogether( $hosh_daysTime, $hosh_lessons, $hosh_professors, $hosh_student_group, $hosh_student_group_course, $hosh_times_of_day, $hosh_classes, $hosh_professors_avalible_lessons, $lessonsListArray, $errorArray, $messageArray  );
    $errorArray = $finalResponse[1];

    /* $set["response"] = $finalResponse[0];
    $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    echo showJasonArrayBeauty( $val ); */
         
    /* فانکشن های اصلی */

    // اتصال دروس با اساتید و گروه های درسی و ساعت و کلاس درس
    function matchInfoTogether( $hosh_daysTime, $hosh_lessons, $hosh_professors, $hosh_student_group, $hosh_student_group_course, $hosh_times_of_day, $hosh_classes, $hosh_professors_avalible_lessons, $lessonsListArray, $errorArray, $messageArray ){
        // ابتدا باید لیستی از اساتیدی که هر درسی را می توانند ارائه کنند تهیه نماییم
        // لیستی از گروه های درسی که برایشان این درس ارائه می شود را پیدا کنیم
        // یک استاد با توجه به روز برایش پیدا کنیم
            // اگر استادی پیدا نکردیم باید استاد سایر کلاس ها را تغییر دهیم
        // یک کد کلاس و ساعت خاصی را بهش نسبت بدیم
            // اگر ساعت مناسب را پیدا نکردیم باید روز را تغییر دهیم و اگر پیدا نکردیم یک کلاس دیگری را لغو نماییم
        
        $tmpHosh_lessons = $hosh_lessons ;
        $tmpHosh_professors = $hosh_professors ;
        $matrixOrgLessons = createEmptyMatrix( $hosh_daysTime );
        //print( printOneMatrix( $matrixOrgLessons ) );
        $ArrayWeeklyClassSchedules = array(); // مجموعه ای از برنامه های هفتگی مربوط به کلاس ها
        for ($k=0; $k < count($hosh_classes) ; $k++) { 
            $classInfo ['info'] = $hosh_classes[$k];
            $classInfo ['weekSchedule'] = $matrixOrgLessons;
            array_push($ArrayWeeklyClassSchedules, $classInfo);
        }
        $propDoWorkMatrix = array(); // ماتریسی از اساتید و روز ها و ساعاتی که اون استاد قرار است تدریس انجام دهد
        $returnArray = array(); // آرایه نهایی که باز میگردانیمش
        // به تعداد تمام درس ها تکرار می کنیم
        foreach ($hosh_lessons as $dars) {
            $rowInfo = array();
            $idDars = $dars[0];
            $findProfessorId = -1 ;
            $groupId = findLessonsGroupId($idDars, $lessonsListArray);
            $rowInfo ['dars_id'] = $idDars;
            $rowInfo ['group_id'] = $groupId;
            $ArrayListOfAllProfCanTeach = findAllProfessorsForOneCourse( $hosh_professors_avalible_lessons, $hosh_professors, $idDars, $groupId, $hosh_professors_avalible_lessons, $hosh_professors );
            $rowInfo ['profs_array'] = $ArrayListOfAllProfCanTeach;
            $response_of_prof_finder = findProfessorForCourse( $tmpHosh_professors, $ArrayListOfAllProfCanTeach, $ArrayWeeklyClassSchedules, $propDoWorkMatrix, $dars );
            $findOneTeacher = $response_of_prof_finder[0];
            if($findOneTeacher>-1){
                $propDoWorkMatrix = $response_of_prof_finder[1];
                $ArrayWeeklyClassSchedules = $response_of_prof_finder[2];
                $rowInfo ['prof_id'] = $findOneTeacher;
                array_push($returnArray,$rowInfo);
            }
            else {
                // استاد درسی برای این درس یافت نشده است
                array_push($errorArray,$messageArray[3].$idDars);
            }
            
        }
        $returnArrayResopnse = array();
        $rowInfoTotal = array();
        $rowInfoTotal['info'] = $returnArray;
        $rowInfoTotal['schedules'] = $ArrayWeeklyClassSchedules;
        $rowInfoTotal['teachers_schedules'] = $propDoWorkMatrix;
        array_push($returnArrayResopnse,$rowInfoTotal);
        return [$returnArrayResopnse, $errorArray];
    }

    // پیدا کردن استاد برای یک درس
    function findProfessorForCourse( $tmpHosh_professors, $ArrayListOfAllProfCanTeach, $ArrayWeeklyClassSchedules, $propDoWorkMatrix, $classInfo ){
        // یافتن استاد با توجه به زمانی که استاد در مرکز آموزش است باید مشخص شود
        // استاد باید توان تدریس آن درس را داشته باشد
        $classId = $classInfo[0];
        $classPartsNo = $classInfo[4];
        $findOneTeacher = -1 ;
        // تکرار به تعداد اساتیدی که توانایی تدریس این درس را دارند
        foreach ($ArrayListOfAllProfCanTeach as $profId) {
            if($findOneTeacher > -1){
                break;
            }
            // پیدا کردن اطلاعات استاد مورد نظر در لیست اساتید
            foreach ($tmpHosh_professors as $profLines) {
                if($findOneTeacher > -1){
                    break;
                }
                if($profLines[0] == $profId){
                    // اطلاعات استاد مورد نظر یافت شد
                    
                    if($classPartsNo < 1){
                        $classPartsNo = 1; // حداقل تعداد برگزاری یک کلاس ، مقدار یک دارد
                    }

                    $findOneTeacherTmp = -1;
                    $loopController = 0 ;
                    // تکرار حلقه به ازای تعداد کلاس هایی که از یک در باید در هر هفته برگزار شود
                    $ArrayWeeklyClassSchedulesTmp = $ArrayWeeklyClassSchedules;
                    $propDoWorkMatrixTmp = $propDoWorkMatrix;

                    for ($countI=0; $countI < $classPartsNo; $countI++) {
                        $findOneTeacherTmp = -1 ;
                        $k = -1;
                        // بدست آوردن برنامه هفتگی هر کلاس
                        foreach ($ArrayWeeklyClassSchedulesTmp as $ClassSchedules) {
                            if($findOneTeacher > -1){
                                break;
                            }
                            $daysId = -1;
                            $k ++;
                        
                            // یافتن اطلاعات برنامه روزانه در هر هفته، مرتبط با این روز
                            foreach ($ClassSchedules['weekSchedule'] as $daysScheules) {
                                if($findOneTeacherTmp > -1){
                                    break;
                                }
                                $daysId ++ ;
                                $hourId = -1 ;
                                // بدست آوردن ساعات مرتبط با هر روز
                                foreach ($daysScheules as $hourScheules) {
                                    if($findOneTeacherTmp > -1){
                                        break;
                                    }
                                    $hourId ++ ;
                                    
                                    // چک کردن اینکه در اون ساعت ،‌کلاس مورد نظر خالی است
                                    if($hourScheules == -1){
                                        // یافتن یک ساعت و روز مناسب برای برگزاری کلاس درس با این استاد
                                        $retValue = checkProfAvailable( $profId, $tmpHosh_professors, $propDoWorkMatrixTmp, $hourId, $daysId, $classId, $k );
                                        $findOneTeacherTmp = $retValue[0];
                                        // در صورت پیدا کردن یک زمان مناسب
                                        if($findOneTeacherTmp > -1){
                                            $propDoWorkMatrixTmp = $retValue[1];
                                            $loopController ++ ;
                                            $ArrayWeeklyClassSchedulesTmp[$k]['weekSchedule'][$daysId][$hourId] = $findOneTeacherTmp;
                                            //echo " <br> $findOneTeacherTmp : [$k][$daysId][$hourId] <br>";
                                        }
                                    }
                                }
                            }
                        }
                    }
                     
                    //echo "$classId ,, findOneTeacherTmp: $findOneTeacherTmp , loopController : $loopController, classPartsNo : $classPartsNo <br>";
                    if($findOneTeacherTmp > -1 && $loopController == $classPartsNo ){
                        $findOneTeacher = $findOneTeacherTmp;
                        $ArrayWeeklyClassSchedules = $ArrayWeeklyClassSchedulesTmp;
                        $propDoWorkMatrix = $propDoWorkMatrixTmp;
                        
                    }
                }
            } 
        }
        return [ $findOneTeacher, $propDoWorkMatrix, $ArrayWeeklyClassSchedules ];
    }

    // بررسی اینکه آیا در این ساعت خاص در این کلاس امکان برگزاری این درس وجود دارد یا خیر
    function checkProfAvailable( $profId, $tmpHosh_professors, $profListMatrix, $hourId, $dayId, $courseId, $classId ){
        $findProf = 0 ;
        $canAddThisClassForProf = -1 ;
        $loopNo = -1 ;
        foreach ($profListMatrix as $rowInfo) {
            $loopNo ++ ;
            if($findProf == 1)
                break;
            if($rowInfo[0] == $profId){
                $findDayAndHour = 0 ;
                $findProf = 1 ;
                $findOtherClass = 0 ;
                

                // گشتن دنبال اینکه آیا این استاد درس دیگری در این تایم دارد که تدریس کند یا خیر
                foreach ($rowInfo[1] as $dayAndHour) {
                    if($findOtherClass == 1)
                        break;
                    if($dayAndHour[0] == $dayId && $dayAndHour[1] == $hourId ){
                        $findOtherClass = 1 ;
                        //echo "1 : dayAndHour[0] : ".$dayAndHour[0]." ,dayId : $dayId ,dayAndHour[1] : ".$dayAndHour[1]." ,hourId : $hourId <br>";
                    }
                    else {
                        //echo "0 : dayAndHour[0] : ".$dayAndHour[0]." ,dayId : $dayId ,dayAndHour[1] : ".$dayAndHour[1]." ,hourId : $hourId <br>";
                    }
                }

                // چک کردن اینکه اصلا آیا استاد در این تاریخ و ساعت در مرکز آموزش هست یا خیر
                if($findOtherClass == 0){
                    $canTeachInThisDayAndHour = 0;
                    foreach ($tmpHosh_professors as $tmp_professors) {
                        if($tmp_professors[0] == $profId){
                            foreach ($tmp_professors[2] as $availableDays) {
                                if($availableDays[0] == -1 || $availableDays[0] == $dayId){
                                    foreach ($availableDays[1] as $availableHours) {
                                        if($availableHours == -1 || $availableHours == $hourId){
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
                    if($canTeachInThisDayAndHour == 0){
                        $findOtherClass = 1 ;
                    }
                }

                // در صورتی که این تایم برای استاد خالی است
                if($findOtherClass == 0){
                    $canAddThisClassForProf = $profId ;
                    // افزودن یک زمان به ماتریس مربوط به اساتید
                    /* ********** */
                    $infoClassRow = array();
                    array_push($infoClassRow,$dayId);// روز تشکیل کلاس
                    array_push($infoClassRow,$hourId);// ساعت تشکیل کلاس
                    array_push($infoClassRow,$courseId);// آیدی درس
                    array_push($infoClassRow,$classId);// آیدی کلاس درس
                    array_push($rowInfo[1],$infoClassRow);

                    $newRowInfo = array();
                    array_push($newRowInfo,$rowInfo[0]);
                    array_push($newRowInfo,$rowInfo[1]);
                    $profListMatrix[$loopNo] = $newRowInfo;
                    /* $infoClassRow2 = array();
                    array_push($infoClassRow2,$infoClassRow);
                    array_push($rowInfo[1],$infoClassRow2); */

                }
            }
        }
        if($findProf == 0){
            // چک کردن اینکه استاد در این روز و ساعت در مرکز آموزش هست یا نیست
            $canTeachInThisDayAndHour = 0;
            foreach ($tmpHosh_professors as $tmp_professors) {
                if($tmp_professors[0] == $profId){
                    foreach ($tmp_professors[2] as $availableDays) {
                        if($availableDays[0] == -1 || $availableDays[0] == $dayId){
                            foreach ($availableDays[1] as $availableHours) {
                                if($availableHours == -1 || $availableHours == $hourId){
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
            if($canTeachInThisDayAndHour == 1){
                $canAddThisClassForProf = $profId;
                // افزودن یک استاد به همراه زمانش به ماتریس مربوط به اساتید
                /* ********** */
                $infoClassRow = array();
                $infoClassRow3 = array();
                array_push($infoClassRow,$dayId);// روز تشکیل کلاس
                array_push($infoClassRow,$hourId);// ساعت تشکیل کلاس
                array_push($infoClassRow,$courseId);// آیدی درس
                array_push($infoClassRow,$classId);// آیدی کلاس درس
                array_push($infoClassRow3,$infoClassRow);
                $infoClassRow2 = array();
                array_push($infoClassRow2,$profId);
                array_push($infoClassRow2,$infoClassRow3);
                array_push($profListMatrix, $infoClassRow2);
            }
        }

        return [ $canAddThisClassForProf, $profListMatrix ];
    }

    // یافتن گروه درس که آیا عمومی یا پایه و یا اصلی است
    function findLessonsGroupId( $lessonsId, $lessonsListArray ){
        $retVal = -1 ;
        $row = 0;
        foreach ($lessonsListArray as $darsArray) {
            $row++;
            if ($retVal != -1)
                break;
            foreach ($darsArray as $darsArrayItems) {
                if ($retVal != -1)
                    break;
                foreach ($darsArrayItems as $lessons){
                    if($lessonsId == $lessons){
                        $retVal = $row;
                        break;
                    }
                }
            }
        }
        return $retVal;
    }

    // لیستی از اساتیدی که می توانند یک درس را درس بدهند پیدا می کند
    function findAllProfessorsForOneCourse( $hosh_professors_avalible_lessons, $hosh_professors, $lessonsId, $groupId ){
        //echo " [$lessonsId, $groupId] <br> ";
        $retArray = array();
        if($lessonsId > -1){
            foreach ($hosh_professors as $professor) {
                $canAdd = 0 ;
                $findProf = 0 ;
                foreach ($hosh_professors_avalible_lessons as $professorCanTeach) {
                    if($findProf==0){
                        if($professorCanTeach[0] == $professor[0]){
                            $findProf = 1 ;
                            foreach ($professorCanTeach[2] as $item) {
                                if($item == $lessonsId){
                                    $canAdd = 1 ;
                                }
                            }
                        }
                    }
                }
                
                if(($canAdd==1 || $findProf==0) && !in_array($professor[0], $retArray))
                    array_push($retArray,$professor[0]);
            }
            if($groupId > -1){
                foreach ($hosh_professors as $professor) {
                $canAdd = 0 ;
                $findProf = 0 ;
                foreach ($hosh_professors_avalible_lessons as $professorCanTeach) {
                    if($findProf == 0){
                        if($professorCanTeach[0] == $professor[0]){
                            $findProf = 1 ;
                            foreach ($professorCanTeach[1] as $item) {
                                if($item == $groupId){
                                    $canAdd = 1 ;
                                }
                            }
                        }
                    }
                }
                if(($canAdd==1 || $findProf==0) && !in_array($professor[0], $retArray))
                    array_push($retArray,$professor[0]);
                }
            }
        }
        else if($groupId > -1){
            foreach ($hosh_professors as $professor) {
                $canAdd = 0 ;
                $findProf = 0 ;
                foreach ($hosh_professors_avalible_lessons as $professorCanTeach) {
                    if($findProf == 0){
                        if($professorCanTeach[0] == $professor[0]){
                            $findProf = 1 ;
                            foreach ($professorCanTeach[1] as $item) {
                                if($item == $groupId){
                                    $canAdd = 1 ;
                                }
                            }
                        }
                    }
                }
                if(($canAdd==1 || $findProf==0) && !in_array($professor[0], $retArray))
                    array_push($retArray,$professor[0]);
            }
        }
        else {
            foreach ($hosh_professors as $professor) {
                if(!in_array($professor[0], $retArray))
                    array_push($retArray,$professor[0]);
            }
        }
        return $retArray ;
    }

    // فانکشنی برای گرفتن تعداد ساعات در کل که استاد در مجموعه حضور دارند
    function findNumberHoursProfessorsCanTeachInTotal( $totalHoursHaveProfessors, $hosh_professors, $daysNo ){
        $totalProfessorsCanTeach = count($hosh_professors);
        $totalHoursHaveProfessors = 0 ;
        for ($i=0; $i < $totalProfessorsCanTeach ; $i++) { 
            $itemArray = $hosh_professors[$i][2];
            $checkAll = false ;
            $countNoHour = 0 ;//یافتن تعداد ساعاتی که یک استاد در اختیار مجموعه برای تدریس است
            $AvrageHoursNo = 0 ;// میانگین ساعت کاری اعلام شده برای استاد
            // پیدا کردن اینکه آیا مقدار -1 در آرایه ثبت شده است یا خیر
            foreach ($itemArray as $item) {
                if (!$checkAll){
                    if($item[0] == -1){
                        $checkAll = true ;
                        $AvrageHoursNo = count($item[1]);
                    }
                }
            }
            // مقدار صفر یا کوچکتر برای میانگین ساعات مامعتبر است
            if($AvrageHoursNo < 1){
                $AvrageHoursNo = 1;
            }
            // درصورتی که استاد به صورت روزانه در اختیار مجموعه است یک مقدار برای میانگین ساعات محاسبه می گردد
            if($checkAll){
                $countNoHour = $daysNo * $AvrageHoursNo;
            }
            foreach ($itemArray as $item) {
                if (!$checkAll){
                    $hour = count($item[1]) ;
                    $countNoHour += $hour;
                }
                else {
                    if(count($item[1]) < $AvrageHoursNo){
                        $countNoHour -= ($AvrageHoursNo-count($item[1]));
                    }
                    else if (count($item[1]) < $AvrageHoursNo){
                        $countNoHour += (count($item[1])-$AvrageHoursNo);
                    }
                }
            }
    
            $totalHoursHaveProfessors += $countNoHour;
        }
        return $totalHoursHaveProfessors;
    }

    // تولید یک ماتریس خالی به عنوان جدول
    function createEmptyMatrix( $days ){
        $rowNo = count($days);
        $matrix = array();
        $colNo = 0 ;
        for ($i=0; $i < $rowNo; $i++) { 
            if ($colNo < count($days[$i][1])){
                $colNo = count($days[$i][1]);
            }  
        }
        for ($i=0; $i < $rowNo ; $i++) { 
            $newRow = array();
            for ($j=0; $j < $colNo; $j++) { 
                array_push($newRow,-1);
            }
            array_push($matrix,$newRow);
        }
        // اعمال محدودیت روی روز هایی که ساعات فعال کمتری دارند
        for ($i=0; $i < $rowNo ; $i++) { 
            for ($j=0; $j < $colNo; $j++) { 
                if( $j > count($days[$i][1])-1 ){
                    $matrix[$i][$j] = -2 ;
                }
            }
        }
        return $matrix;
    }

    // تبدیل یک ماتریس به یک متن
    function printOneMatrix( $matrix ){
        $rowNo = count($matrix);
        $varText = "[ " ;
        for ($i=0; $i < $rowNo ; $i++) { 
            $line = "&nbsp;&nbsp;&nbsp;" ;
            for ($j=0; $j < count($matrix[$i]); $j++) { 
                if($j+1 == count($matrix[$i])){
                    $line .=  $matrix[$i][$j] ;
                }
                else {
                    $line .=  $matrix[$i][$j] .", &nbsp;" ;
                }
            }
            $varText .= "<br>". $line ;
        }
        return $varText." <br> ]";
    }

    // تبدیل یک متن با قالب جیسون به متنی گرافیکی متناسب
    function showJasonArrayBeauty( $JasonText ){
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

?>

    <!-- <?php 
    if(count($errorArray)>0){
        foreach ($errorArray as $txtError) {
    ?>
    <p style="color:red;padding:10px;font-weight: bold;font-family: system-ui;" ><?php echo $txtError;?></p>
    <?php }
    }
    else {
        ?>
        <p style="color:green;padding:10px;font-weight: bold;font-family: system-ui;" ><?php echo $messageArray[1];?></p>
        <?php
    } ?> -->

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <style>
            @font-face {
                font-family: IRANSans;
                font-style: normal;
                font-weight: 200;
                src: url('../assets/font/eot/IRANSansWeb_UltraLight.eot');
                src: url('../assets/font/eot/IRANSansWeb_UltraLight.eot?#iefix') format('embedded-opentype'),  /* IE6-8 */
                    url('../assets/font/woff2/IRANSansWeb_UltraLight.woff2') format('woff2'),  /* FF39+,Chrome36+, Opera24+*/
                    url('../assets/font/woff/IRANSansWeb_UltraLight.woff') format('woff'),  /* FF3.6+, IE9, Chrome6+, Saf5.1+*/
                    url('../assets/font/ttf/IRANSansWeb_UltraLight.ttf') format('truetype');
            }
            body {
                font-family: IRANSans;
                direction: rtl;
            }
            
            .panel-body{
                overflow: auto;
            }

            .filter_day {
                background: rgb(255,166,7,0.1);/* hsl(33,100,53,0.15); */
            }

            table {
                border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
                border: 1px solid #ddd;
                direction: rtl;
                text-align: center;
            }

            th, td {
                text-align: center;
                padding: 8px;
            }

            tr:nth-child(even){background-color: #f2f2f2}
        </style>
    </head>
    <body>
        <div class="container">
            <h2>پاسخ نهایی</h2>
            <p></p>
            <div class="panel-group" id="accordion">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">جداول کلاس ها</a>
                        </h4>
                    </div>
                    <div id="collapse1" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <?php 
                            $class_list_array = $finalResponse[0][0]["schedules"];
                            $classId = -1 ;
                            foreach ($class_list_array as $class_info) {
                                $class_name = $class_info["info"][0];
                                $classId++;
                            ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#collapse1" href="#collapse1_<?php echo $classId; ?>">کلاس : <?php echo $class_name; ?></a>
                                    </h4>
                                </div>
                                <div id="collapse1_<?php echo $classId;?>" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <p>
                                        جدول مربوط به اطلاعات کلاس ها
                                        </p>
                                        <table>
                                            <tr>
                                                <th></th>
                                            <?php 
                                            /* گرفتن اطلاعات مربوط به ساعات تشکیل کلاس */
                                            foreach ($hosh_times_of_day as $timesOfDay) {
                                                $dayTimeTitle = "";
                                                foreach ($hosh_time_titles as $dayTitle) {
                                                    if($dayTitle[0] == $timesOfDay[3]){
                                                        $dayTimeTitle = $dayTitle[1][0] . "-" . $dayTitle[1][1];
                                                        break;
                                                    }
                                                }
                                                
                                                ?>
                                                <th><?php echo $dayTimeTitle;?></th>
                                            <?php } ?>
                                            </tr>
                                            <?php 
                                            $daysCounter = -1;
                                            foreach ($hosh_daysTime as $hosh_daysTimeItem) {
                                                $daysCounter ++ ; 
                                                ?>
                                            <tr>
                                                <!-- عنوان روز -->
                                                <td><p style="" ><?php echo  $hosh_daysTimeItem[0];?></p></td>
                                                <?php 
                                                    $mValidHour = -1;
                                                    
                                                    foreach ($hosh_times_of_day as $timesOfDay) {
                                                        $findClass = 0 ;
                                                        $onceShown = 0;
                                                        if(in_array($timesOfDay[0],$hosh_daysTimeItem[1])){
                                                            $mValidHour++;
                                                            foreach ($class_info["weekSchedule"][$daysCounter] as $schedulesDaysInfo) {
                                                                if($onceShown == 0){
                                                                    $prof_info = array();
                                                                    $arrayInfoClass = array();
                                                                    $prof_name = "";
                                                                    $prof_id = -1;
                                                                    $prof_list = $finalResponse[0][0]["teachers_schedules"];
                                                                    foreach ($prof_list as $prof_info_) {
                                                                        $prof_id = $prof_info_[0];// id ostad
                                                                        if($schedulesDaysInfo == $prof_id){
                                                                            foreach ($hosh_professors as $all_prof_info) {
                                                                                if($prof_id == $all_prof_info[0]){
                                                                                    $prof_name = $all_prof_info[1];
                                                                                    $prof_info = $prof_info_;
                                                                                    break;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    
                                                                    if(count($prof_info)>1){
                                                                        $arrayInfoClass = $prof_info[1];
                                                                        foreach ($arrayInfoClass as $keyOfDay) {
                                                                            if($keyOfDay[0] == $hosh_daysTimeItem[2] && $keyOfDay[1] == $mValidHour){
                                                                                $findClass = 1;
                                                                                $className = "";
                                                                                $courseName = "";
                                                                                $courseCode = "";
                                                                                $counterClass = 0 ;
                                                                                foreach ($hosh_classes as $classItems) {
                                                                                    if($counterClass == $keyOfDay[3]){
                                                                                        $className = $classItems[0];
                                                                                        break;
                                                                                    }
                                                                                    $counterClass++;
                                                                                }
                                                                                foreach ($hosh_lessons as $lessonsInfoArray) {
                                                                                    if($lessonsInfoArray[0] == $keyOfDay[2]){
                                                                                        $courseName = $lessonsInfoArray[3];
                                                                                        $courseCode = $lessonsInfoArray[2];
                                                                                    }
                                                                                }
                                                                            ?>
                                                    <td class="<?php if($timesOfDay[2] == 1){echo " filter_day ";}?>" ><p ><?php echo  "درس ".$courseName;?></p><p style="" ><?php echo  "کد ($courseCode)";?></p><p style="" ><?php echo  "استاد :‌ $prof_name";?></p></td>
                                                                        <?php   $onceShown = 1 ;
                                                                        break; }
                                                                        } 
                                                                    } 
                                                                } 
                                                            }
                                                        if( $findClass == 0 && $onceShown == 0) {
                                                            $onceShown =1 ?>
                                                    <td class="<?php if($timesOfDay[2] == 1){echo " filter_day ";}?>"><p ><?php echo  "_";?></p></td>
                                                        <?php
                                                        }
                                                    } else { ?>
                                                    <td class="<?php if($timesOfDay[2] == 1){echo " filter_day ";}?>" disabled ><!-- background: red; --></td>
                                                <?php } 
                                                } ?>
                                            </tr>
                                            <?php
                                            }
                                            ?>
                                            <?php ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php 
                            } ?>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">جداول اساتید</a>
                        </h4>
                    </div>
                    <div id="collapse2" class="panel-collapse collapse">
                        <div class="panel-body">
                            <?php 
                            $prof_list = $finalResponse[0][0]["teachers_schedules"];
                            foreach ($prof_list as $prof_info) {
                                $prof_name = "";
                                $prof_id = $prof_info[0];// id ostad
                                foreach ($hosh_professors as $all_prof_info) {
                                    if($prof_id == $all_prof_info[0]){
                                        $prof_name = $all_prof_info[1];
                                        break;
                                    }
                                }
                            ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#collapse2" href="#collapse2_<?php echo $prof_id; ?>">استاد : <?php echo $prof_name; ?></a>
                                    </h4>
                                </div>
                                <div id="collapse2_<?php echo $prof_id;?>" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <p>
                                        جدول مربوط به اطلاعات اساتید
                                        </p>
                                        <table>
                                            <tr>
                                                <th></th>
                                            <?php 
                                            foreach ($hosh_times_of_day as $timesOfDay) {
                                                $dayTimeTitle = "";
                                                foreach ($hosh_time_titles as $dayTitle) {
                                                    if($dayTitle[0] == $timesOfDay[3]){
                                                        $dayTimeTitle = $dayTitle[1][0] . "-" . $dayTitle[1][1];
                                                        break;
                                                    }
                                                }
                                                ?>
                                                <th><?php echo $dayTimeTitle;?></th>
                                            <?php } ?>
                                            </tr>
                                            <?php ?>
                                            <?php foreach ($hosh_daysTime as $hosh_daysTimeItem) {
                                                ?>
                                            <tr>
                                                <!-- عنوان روز -->
                                                <td><p style="" ><?php echo  $hosh_daysTimeItem[0];?></p></td>
                                                <?php 
                                                    $mValidHour = -1;
                                                    foreach ($hosh_times_of_day as $timesOfDay) { 
                                                        if(in_array($timesOfDay[0],$hosh_daysTimeItem[1])){
                                                            $mValidHour++;
                                                            $findClass = 0 ;
                                                            $arrayInfoClass = $prof_info[1];
                                                            foreach ($arrayInfoClass as $keyOfDay) {
                                                                if($keyOfDay[0] == $hosh_daysTimeItem[2] && $keyOfDay[1] == $mValidHour){
                                                                    $findClass = 1;
                                                                    $className = "";
                                                                    $courseName = "";
                                                                    $courseCode = "";
                                                                    $counterClass = 0 ;
                                                                    foreach ($hosh_classes as $classItems) {
                                                                        if($counterClass == $keyOfDay[3]){
                                                                            $className = $classItems[0];
                                                                            break;
                                                                        }
                                                                        $counterClass++;
                                                                    }
                                                                    foreach ($hosh_lessons as $lessonsInfoArray) {
                                                                        if($lessonsInfoArray[0] == $keyOfDay[2]){
                                                                            $courseName = $lessonsInfoArray[3];
                                                                            $courseCode = $lessonsInfoArray[2];
                                                                        }
                                                                    }
                                                                ?>
                                                    <td class="<?php if($timesOfDay[2] == 1){echo " filter_day ";}?>" ><p ><?php echo  "درس ".$courseName;?></p><p style="" ><?php echo  "کد ($courseCode)";?></p><p style="" ><?php echo  "کلاس :‌ $className";?></p></td>
                                                            <?php   break; }
                                                            } if( $findClass == 0) {?>
                                                    <td class="<?php if($timesOfDay[2] == 1){echo " filter_day ";}?>"><p ><?php echo  "_";?></p></td>
                                                            <?php
                                                            } 
                                                        } else { ?>
                                                    <td class="<?php if($timesOfDay[2] == 1){echo " filter_day ";}?>" disabled ><!-- background: red; --></td>
                                                    <?php } 
                                                } ?>
                                            </tr>
                                            <?php
                                            }
                                            ?>
                                            <?php ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">جداول گروه های درسی</a>
                        </h4>
                    </div>
                    <div id="collapse3" class="panel-collapse collapse ">
                        <div class="panel-body">
                            <!-- پنل داخلی -->
                            <?php 
                                foreach ($hosh_student_group as $student_group_info) {
                                    $groupStudentId = $student_group_info[0];
                                    $groupStudentTitle = $student_group_info[1];
                                ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#collapse3" href="#collapse3_<?php echo $groupStudentId;?>">گروه : <?php echo $groupStudentTitle;?></a>
                                    </h4>
                                </div>
                                <div id="collapse3_<?php echo $groupStudentId;?>" class="panel-collapse collapse ">
                                    <div class="panel-body">
                                        <?php 
                                        $class_list_array = $finalResponse[0][0]["schedules"];
                                        $classId = -1 ;
                                        foreach ($class_list_array as $class_info) {
                                            $class_name = $class_info["info"][0];
                                            $classId++;
                                        ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#collapse3_<?php echo $groupStudentId;?>" href="#collapse3_<?php echo $groupStudentId;?>_<?php echo $classId; ?>">کلاس : <?php echo $class_name; ?></a>
                                                </h4>
                                            </div>
                                            <div id="collapse3_<?php echo $groupStudentId;?>_<?php echo $classId;?>" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <p>
                                                    جدول مربوط به اطلاعات کلاس ها
                                                    </p>
                                                    <table>
                                                        <tr>
                                                            <th></th>
                                                        <?php 
                                                        /* گرفتن اطلاعات مربوط به ساعات تشکیل کلاس */
                                                        foreach ($hosh_times_of_day as $timesOfDay) {
                                                            $dayTimeTitle = "";
                                                            foreach ($hosh_time_titles as $dayTitle) {
                                                                if($dayTitle[0] == $timesOfDay[3]){
                                                                    $dayTimeTitle = $dayTitle[1][0] . "-" . $dayTitle[1][1];
                                                                    break;
                                                                }
                                                            }
                                                            
                                                            ?>
                                                            <th><?php echo $dayTimeTitle;?></th>
                                                        <?php } ?>
                                                        </tr>
                                                        <?php 
                                                        $daysCounter = -1;
                                                        foreach ($hosh_daysTime as $hosh_daysTimeItem) {
                                                            $daysCounter ++ ; 
                                                            ?>
                                                        <tr>
                                                            <!-- عنوان روز -->
                                                            <td><p style="" ><?php echo  $hosh_daysTimeItem[0];?></p></td>
                                                            <?php 
                                                                $mValidHour = -1;
                                                                
                                                                foreach ($hosh_times_of_day as $timesOfDay) {
                                                                    $findClass = 0 ;
                                                                    $onceShown = 0;
                                                                    if(in_array($timesOfDay[0],$hosh_daysTimeItem[1])){
                                                                        $mValidHour++;
                                                                        foreach ($class_info["weekSchedule"][$daysCounter] as $schedulesDaysInfo) {
                                                                            if($onceShown == 0){
                                                                                $prof_info = array();
                                                                                $arrayInfoClass = array();
                                                                                $prof_name = "";
                                                                                $prof_id = -1;
                                                                                $prof_list = $finalResponse[0][0]["teachers_schedules"];
                                                                                foreach ($prof_list as $prof_info_) {
                                                                                    $prof_id = $prof_info_[0];// id ostad
                                                                                    if($schedulesDaysInfo == $prof_id){
                                                                                        foreach ($hosh_professors as $all_prof_info) {
                                                                                            if($prof_id == $all_prof_info[0]){
                                                                                                $prof_name = $all_prof_info[1];
                                                                                                $prof_info = $prof_info_;
                                                                                                break;
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                                
                                                                                if(count($prof_info)>1){
                                                                                    $arrayInfoClass = $prof_info[1];
                                                                                    foreach ($arrayInfoClass as $keyOfDay) {
                                                                                        $studentGroupLessonFilter = 0 ;
                                                                                        if($keyOfDay[0] == $hosh_daysTimeItem[2] && $keyOfDay[1] == $mValidHour){
                                                                                            $findClass = 1;
                                                                                            $className = "";
                                                                                            $courseName = "";
                                                                                            $courseCode = "";
                                                                                            $counterClass = 0 ;
                                                                                            foreach ($hosh_classes as $classItems) {
                                                                                                if($counterClass == $keyOfDay[3]){
                                                                                                    $className = $classItems[0];
                                                                                                    break;
                                                                                                }
                                                                                                $counterClass++;
                                                                                            }
                                                                                            foreach ($hosh_lessons as $lessonsInfoArray) {
                                                                                                if($lessonsInfoArray[0] == $keyOfDay[2]){
                                                                                                    $courseName = $lessonsInfoArray[3];
                                                                                                    $courseCode = $lessonsInfoArray[2];
                                                                                                }
                                                                                            }
                                                                                            foreach ($hosh_student_group_course as $filterGroupCourse) {
                                                                                                if($filterGroupCourse[0] == $keyOfDay[2]){
                                                                                                    $studentGroupLessonFilter = 1;
                                                                                                    if($filterGroupCourse[1] == $groupStudentId){
                                                                                                        $studentGroupLessonFilter = 0;
                                                                                                    }
                                                                                                    break;
                                                                                                }
                                                                                            }
                                                                                            if($studentGroupLessonFilter == 0){
                                                                                        ?>
                                                                <td class="<?php if($timesOfDay[2] == 1){echo " filter_day ";}?>" ><p ><?php echo  "درس ".$courseName;?></p><p style="" ><?php echo  "کد ($courseCode)";?></p><p style="" ><?php echo  "استاد :‌ $prof_name";?></p></td>
                                                                                    <?php } else { ?>
                                                                <td class="<?php if($timesOfDay[2] == 1){echo " filter_day ";}?>"><p ><?php echo  "_";?></p></td>
                                                                                    <?php }
                                                                                    $onceShown = 1 ;
                                                                                    break; }
                                                                                    } 
                                                                                } 
                                                                            } 
                                                                        }
                                                                    if( $findClass == 0 && $onceShown == 0) {
                                                                        $onceShown =1 ?>
                                                                <td class="<?php if($timesOfDay[2] == 1){echo " filter_day ";}?>"><p ><?php echo  "_";?></p></td>
                                                                    <?php
                                                                    }
                                                                } else { ?>
                                                                <td class="<?php if($timesOfDay[2] == 1){echo " filter_day ";}?>" disabled ><!-- background: red; --></td>
                                                            <?php } 
                                                            } ?>
                                                        </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                        <?php ?>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <?php 
                                        } ?>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                
            </div> 
        </div>
    </body>
</html>
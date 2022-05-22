@extends('layout.app')
@section('title', 'کلاس ها')
@section('content')
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
                            <a data-toggle="collapse" data-parent="#collapse1" href="#collapse1_<?php echo $classId; ?>">کلاس :
                                <?php echo $class_name; ?></a>
                        </h4>
                    </div>
                    <div id="collapse1_<?php echo $classId; ?>" class="panel-collapse collapse">
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
                                    <th><?php echo $dayTimeTitle; ?></th>
                                    <?php } ?>
                                </tr>
                                <?php
                                            $daysCounter = -1;
                                            foreach ($hosh_daysTime as $hosh_daysTimeItem) {
                                                $daysCounter ++ ;
                                                ?>
                                <tr>
                                    <!-- عنوان روز -->
                                    <td>
                                        <p style=""><?php echo $hosh_daysTimeItem[0]; ?></p>
                                    </td>
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
                                    <td class="<?php if ($timesOfDay[2] == 1) {
                                        echo ' filter_day ';
                                    } ?>">
                                        <p><?php echo 'درس ' . $courseName; ?></p>
                                        <p style=""><?php echo "کد ($courseCode)"; ?></p>
                                        <p style=""><?php echo "استاد :‌ $prof_name"; ?></p>
                                    </td>
                                    <?php   $onceShown = 1 ;
                                                                        break; }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        if( $findClass == 0 && $onceShown == 0) {
                                                            $onceShown =1 ?>
                                    <td class="<?php if ($timesOfDay[2] == 1) {
                                        echo ' filter_day ';
                                    } ?>">
                                        <p><?php echo '_'; ?></p>
                                    </td>
                                    <?php
                                                        }
                                                    } else { ?>
                                    <td class="<?php if ($timesOfDay[2] == 1) {
                                        echo ' filter_day ';
                                    } ?>" disabled>
                                        <!-- background: red; -->
                                    </td>
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
@endsection

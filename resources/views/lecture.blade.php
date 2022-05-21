@extends('layout.app')
@section('title', 'اساتید')
@section('content')
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
            <th><?php echo $dayTimeTitle; ?></th>
            <?php } ?>
        </tr>
        <?php ?>
        <?php foreach ($hosh_daysTime as $hosh_daysTimeItem) {
                                                ?>
        <tr>
            <!-- عنوان روز -->
            <td>
                <p style=""><?php echo $hosh_daysTimeItem[0]; ?></p>
            </td>
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
            <td class="<?php if ($timesOfDay[2] == 1) {
                echo ' filter_day ';
            } ?>">
                <p><?php echo $courseName; ?></p>
                <p style=""><?php echo "کد ($courseCode)"; ?></p>
                <p style=""><?php echo "کلاس :‌ $className"; ?></p>
            </td>
            <?php   break; }
                                                            } if( $findClass == 0) {?>
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
@endsection

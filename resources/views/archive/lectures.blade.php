@extends("layout.app")
@section("title", "اساتید")
@section("content")
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
                                foreach ($professors as $all_prof_info) {
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
                                            foreach ($times_of_day as $timesOfDay) {
                                                $dayTimeTitle = "";
                                                foreach ($time_titles as $dayTitle) {
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
                                            <?php foreach ($daysTime as $daysTimeItem) {
                                                ?>
                                            <tr>
                                                <!-- عنوان روز -->
                                                <td><p style="" ><?php echo  $daysTimeItem[0];?></p></td>
                                                <?php
                                                    $mValidHour = -1;
                                                    foreach ($times_of_day as $timesOfDay) {
                                                        if(in_array($timesOfDay[0],$daysTimeItem[1])){
                                                            $mValidHour++;
                                                            $findClass = 0 ;
                                                            $arrayInfoClass = $prof_info[1];
                                                            foreach ($arrayInfoClass as $keyOfDay) {
                                                                if($keyOfDay[0] == $daysTimeItem[2] && $keyOfDay[1] == $mValidHour){
                                                                    $findClass = 1;
                                                                    $className = "";
                                                                    $courseName = "";
                                                                    $courseCode = "";
                                                                    $counterClass = 0 ;
                                                                    foreach ($classes as $classItems) {
                                                                        if($counterClass == $keyOfDay[3]){
                                                                            $className = $classItems[0];
                                                                            break;
                                                                        }
                                                                        $counterClass++;
                                                                    }
                                                                    foreach ($lessons as $lessonsInfoArray) {
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
@endsection

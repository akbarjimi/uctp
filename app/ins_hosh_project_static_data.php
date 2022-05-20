<?php
/* برای پروژه درس هوش مصنوعی پیشرفته */

// اولین مقدار برای آیدی است
// دومین مقدار برای زوج یا فرد بودن است 
// سومین مقدار برای اینکه نشان دهیم که ساعت صبح است یا بعد از ظهر
// مقدار 4 آیتم آیدی آرایه $hosh_time_titles از ساعات شروع و پایان
$hosh_times_of_day = [[0,0,0,0],[1,0,0,1],[2,0,0,2],
    [3,1,0,3],[4,1,0,4],[5,0,1,5],
    [6,0,1,6],[7,1,1,7],[8,1,1,8]];


$hosh_time_titles = [
    [0,["8:00","9:00"]],
    [1,["9:30","10:30"]],
    [2,["11:00","12:00"]],
    [3,["7:30","9:00"]],
    [4,["9:30","11:00"]],
    [5,["13:00","14:00"]],
    [6,["14:30","15:30"]],
    [7,["13:30","15:00"]],
    [8,["15:30","17:00"]],
];

// روز های هفته که میتوان در آنها درس ارائه دهیم
// آیتم اول نام روز و آیتم دوم ساعاتی که می توان در آنها کلاس برگذار کرد
// سومین آیتم مربوط به ایدی روز
$hosh_daysTime = [
    ["شنبه",[0,1,2,5,6],0],
    ["یکشنبه",[3,4,7,8],1],
    ["دوشنبه",[0,1,2,5,6],2],
    ["سه شنبه",[3,4,7,8],3],
    ["چهارشنبه",[0,1,2,5,6],4],
];

// نام کلاس ها
// اولین آیتم نام کلاس و دومین آیتم مربوط به میزان تجهیز بودن کلاس می باشد
$hosh_classes = [
    ["A",0], ["B",0], ["c",0], ["d",1], ["e",1], ["f",2], ["g",2], ["h",3]
];

// نام اساتید
// تعداد اساتید از یک تا 15 هست
// [id, "professors name", [[day_id,[hour_id1,...,hour_idn in day 1]],[day2_id,[hour_id1,...,hour_idn in day 2]]],
// -1 mean all days or all hour
$hosh_professors = [
    [0,"L1",[[0,[0,2]],[2,[0,1,5]]]],
    [1,"L2",[[1,[3,4,7]],[2,[-1]]]],
    [2,"L3",[[-1,[-1]]]],
    [3,"L4",[[-1,[-1]]]],
    [4,"L5",[[-1,[-1]]]],
    [5,"L6",[[-1,[-1]]]],
    [6,"L7",[[1,[3]]]],
    [7,"L8",[[-1,[-1]]]],
    [8,"L9",[[-1,[-1]]]],
    [9,"L10",[[-1,[-1]]]],
    [10,"L11",[[-1,[-1]]]],
    [11,"L12",[[-1,[-1]]]],
    [12,"L13",[[-1,[-1]]]],
    [13,"L14",[[-1,[-1]]]],
    [15,"L15",[[-1,[-1]]]],
];

//ترکیب اساتید با دروسی که می توانند درس بدهند
// نام اساتیدی که در لیست نیست به این معنی است که استاد درس توانایی بالایی دارد و میتواند هر چیزی را درس دهد
// آیتم اول مربوط به آیدی استادو آیتم دوم آرایه ای از گروه های درسی که استاد مهارت دارد و آیتم سومی مربوط به دروسی است که می تواند درس دهد
$hosh_professors_avalible_lessons = [
    [0,[2],[21,22,23]],
    [3,[1],[]],
    [1,[2],[21,22,23]],
    [5,[1,2],[]],
    [2,[1],[]],
    [4,[0,1],[]],
];

// عنوان دروس 
// [id in list, id darse, code darse, onvan darse, tedad dars dar hafte]
$hosh_lessons = [
    [0, 0, "101","DG1",1],
    [1, 1, "102","DG2",1],
    [2, 2, "103","DG3",1],
    [3, 3, "104","DG4",1],
    [4, 4, "105","DG5",1],
    [5, 5, "106","DG6",1],
    [6, 6, "107","DG7",1],
    [7, 7, "108","DG8",1],
    [8, 8, "109","DG9",1],

    [9, 9, "201","DP1",1],
    [10, 10, "202","DP2",1],
    [11, 11, "203","DP3",1],
    [12, 12, "204","DP4",1],
    [13, 13, "205","DP5",1],
    [14, 14, "206","DP6",1],
    [15, 15, "207","DP7",1],
    [16, 16, "208","DP8",1],
    [17, 17, "209","DP9",1],
    [18, 18, "210","DP10",1],
    [19, 19, "211","DP11",1],
    [20, 20, "212","DP12",1],

    [21, 21, "301","DS1",2],
    [22, 22, "302","DS2",2],
    [23, 23, "303","DS3",2],
    [24, 24, "304","DS4",2],
    [25, 25, "305","DS5",2],
    [26, 26, "306","DS6",2],
    [27, 27, "307","DS7",2],
    [28, 28, "308","DS8",2],
    [29, 29, "309","DS9",2],
    [30, 30, "310","DS10",2],
    [31, 31, "311","DS11",2],
    [32, 32, "312","DS12",2],
    [33, 33, "313","DS13",2],
    [34, 34, "314","DS14",2],
    [35, 35, "315","DS15",2],
    [36, 36, "316","DS16",2],
    [37, 37, "317","DS17",2],
    [38, 38, "318","DS18",2],
    [39, 39, "319","DS19",2],
    [40, 40, "320","DS20",2],
    [41, 41, "321","DS21",2],
    [42, 42, "322","DS22",2],
    [43, 43, "323","DS23",2],
    [44, 44, "324","DS24",2],
    [45, 45, "325","DS25",2],

    [46, 45, "326","DS25",2],
    [47, 45, "327","DS25",3],
    [48, 45, "328","DS25",2],
    [49, 45, "329","DS25",2],
    [50, 44, "330","DS24",2],
    [51, 44, "331","DS24",2],
    [52, 44, "332","DS24",2],
    [53, 44, "333","DS24",2],
    [54, 44, "334","DS24",2],

];
/* 
     */

// نام درس های عمومی
// تعداد دروس از یک تا 9
// اولین آیتم مربوط به آیدی کلاس
// آیتم دوم مربوط به حداقل کیفیت کلاس که به صورت یک عدد از صفر به بالا می باشد
// سومین آیتم مربوط به درس پیشنیاز می باشد - اگر مقدارش -1 باشد یعنی هیچ پیشنیازی ندارد
// آیتم چهارم مربوط به دروس هم نیاز
// آیدی آخر مربوط به کد درس
$hosh_general_lessons = [
    [0, 0, -1, []],
    [1, 0, -1, []],
    [2, 0, -1, []],
    [3, 0, -1, []],
    [4, 0, -1, []],
    [5, 0, -1, []],
    [6, 0, -1, []],
    [7, 0, -1, []],
    [8, 0, -1, []],
];

// نام دروس پایه
// تعداد دروس از یک تا 12 می باشد
$hosh_basic_lessons = [
    [9, 0, -1, []],
    [10, 0, -1, []],
    [11, 0, 10, []],
    [12, 0, 11, []],
    [13, 0, 12, []],
    [14, 0, -1, []],
    [15, 0, 9, [14,10]],
    [16, 0, -1, [14]],
    [17, 0, -1, []],
    [18, 0, -1, []],
    [19, 0, -1, []],
    [20, 0, -1, []],
];

// نام دروس اصلی
// تعداد دروس از یک تا 25 می باشد
$hosh_main_courses = [
    [21, 1, -1, []],
    [22, 1, -1, []],
    [23, 1, 22, []],
    [24, 1, 23, []],
    [25, 1, 24, []],
    [26, 1, -1, []],
    [27, 1, -1, []],
    [28, 1, -1, []],
    [29, 1, -1, []],
    [30, 1, -1, []],
    [31, 2, -1, []],
    [32, 2, -1, []],
    [33, 2, -1, []],
    [34, 2, -1, []],
    [35, 2, -1, []],
    [36, 2, -1, []],
    [37, 0, -1, []],
    [38, 0, -1, []],
    [39, 0, -1, []],
    [40, 0, -1, []],
    [41, 0, -1, []],
    [42, 0, -1, []],
    [43, 0, -1, []],
    [44, 0, -1, []],
    [45, 0, -1, []],
];

//نام گروه بندی های مربوط به دانشجویان
$hosh_student_group = [
    [0,"SY1"], [1,"SY2"], [2,"SY3"], [3,"SY4"],
];

// ارتباط میان گروه های دانشجویی و دروس با یکدیگر
// آیدی اول مربوط به درس و آیدی دوم مربوط به گروه درسی است
// آیدی دروسی که در لیست نیستند مربوط به تمامی دانشجویان می باشد
$hosh_student_group_course = [
    [31,0], [31,1], [31,2], 
    [32,0],
    [33,1], [33,2], 
    [34,1], [34,2], [34,3],
];


include("ins_hosh_project_static_data_algorithm.php");

?>
<?php

namespace Database\Seeders;

use App\Models\StudentYear;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            DaysTimeSeeder::class,
            RoomSeeder::class,
            CourseTypeSeeder::class,
            CourseSeeder::class,
            LessonSeeder::class,
            StudentYearSeeder::class,
            DaysSeeder::class,
            TimeSeeder::class,
            LectureSeeder::class,
        ]);
    }
}

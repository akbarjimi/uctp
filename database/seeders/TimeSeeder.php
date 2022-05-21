<?php

namespace Database\Seeders;

use App\Models\Time;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $times = [
            ["8:00", "9:00"],
            ["9:30", "10:30"],
            ["11:00", "12:00"],
            ["7:30", "9:00"],
            ["9:30", "11:00"],
            ["13:00", "14:00"],
            ["14:30", "15:30"],
            ["13:30", "15:00"],
            ["15:30", "17:00"],
        ];

        foreach ($times as $time) {
            Time::create([
                "start" => $time[0],
                "end" => $time[1],
            ]);
        }
    }
}

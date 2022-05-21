<?php

namespace Database\Seeders;

use App\Models\Days;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $days = [

            [0, 0, 0, 0],
            [1, 0, 0, 1],
            [2, 0, 0, 2],
            [5, 0, 1, 5],
            [6, 0, 1, 6],

            [3, 1, 0, 3],
            [4, 1, 0, 4],
            [7, 1, 1, 7],
            [8, 1, 1, 8]
        ];

        foreach ($days as $day) {
            Days::create([
                "odd" => $day[1],
                "evening" => $day[2],
                "time" => $day[3]+1,
            ]);
        }
    }
}

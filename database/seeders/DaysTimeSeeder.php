<?php

namespace Database\Seeders;

use App\Models\DaysTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DaysTimeSeeder extends Seeder
{
    public function run()
    {
        $daystimes = [
            ["شنبه", [0, 1, 2, 5, 6]],
            ["یکشنبه", [3, 4, 7, 8]],
            ["دوشنبه", [0, 1, 2, 5, 6]],
            ["سه شنبه", [3, 4, 7, 8]],
            ["چهارشنبه", [0, 1, 2, 5, 6]],
        ];

        foreach ($daystimes as $daystime) {
            DaysTime::create([
                "name" => $daystime[0],
                "hours" => $daystime[1],
            ]);
        }
    }
}

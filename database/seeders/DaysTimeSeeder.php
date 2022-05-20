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
            ["Saturday", [0, 1, 2, 5, 6]],
            ["Sunday", [3, 4, 7, 8]],
            ["Monday", [0, 1, 2, 5, 6]],
            ["Thursday", [3, 4, 7, 8]],
            ["Wednesday", [0, 1, 2, 5, 6]],
        ];

        foreach ($daystimes as $daystime) {
            DaysTime::create([
                "name" => $daystime[0],
                "hours" => $daystime[1],
            ]);
        }
    }
}

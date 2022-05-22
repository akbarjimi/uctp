<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run()
    {
        $rooms = [
            ["الف", 0], ["ب", 0], ["پ", 0], ["ث", 1], ["ج", 1], ["چ", 2], ["ح", 2], ["خ", 3]
        ];

        foreach ($rooms as $room) {
            Room::create([
                "name" => $room[0],
                "equipped" => $room[1],
            ]);
        }
    }
}

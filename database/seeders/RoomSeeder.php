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
            ["A", 0], ["B", 0], ["C", 0], ["D", 1], ["E", 1], ["F", 2], ["G", 2], ["H", 3]
        ];

        foreach ($rooms as $room) {
            Room::create([
                "name" => $room[0],
                "equipped" => $room[1],
            ]);
        }
    }
}

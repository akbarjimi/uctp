<?php

namespace Database\Seeders;

use App\Models\CourseType;
use Illuminate\Database\Seeder;

class CourseTypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            [
                "general",
                "عمومی",
            ],
            [
                "basic",
                "پایه",
            ],
            [
                "main",
                "اصلی",
            ],
        ];

        foreach ($types as $type) {
            CourseType::create([
                "name" => $type[0],
                "alias" => $type[1],
            ]);
        }
    }
}

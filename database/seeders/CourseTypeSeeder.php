<?php

namespace Database\Seeders;

use App\Models\CourseType;
use Illuminate\Database\Seeder;

class CourseTypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            "general",
            "basic",
            "main",
        ];

        foreach ($types as $type) {
            CourseType::create([
                "name" => $type,
            ]);
        }
    }
}

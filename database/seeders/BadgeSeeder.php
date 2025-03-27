<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Badge;


class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $badges = [
            [
                'name' => 'Mentor Expert',
                'description' => 'A publié 5 cours et a 50 étudiants',
                'type' => 'mentor',
                'conditions' => [
                    'min_courses' => 5,
                    'min_students' => 50,
                    'min_months_active' => 6
                ],
                'image_url' => 'default_badge.png', 
            ],
           
        ];
    
        foreach ($badges as $badge) {
            Badge::create($badge);
        }
    }
}

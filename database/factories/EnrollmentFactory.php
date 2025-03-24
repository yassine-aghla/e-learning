<?php

namespace Database\Factories;

use App\Models\Enrollment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnrollmentFactory extends Factory
{
    protected $model = Enrollment::class;

    public function definition()
    {
        return [
            'course_id' => Course::factory(),
            'user_id' => User::factory(),
            'status' => $this->faker->randomElement(['pending', 'accepted', 'rejected']),
        ];
    }
}
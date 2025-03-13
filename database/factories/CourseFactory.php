<?php
namespace Database\Factories;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->text(200),
            'content' => $this->faker->text,
            'video' => $this->faker->url,
            'cover' => $this->faker->imageUrl(),
            'duration' => $this->faker->randomFloat(2, 1, 100),
            'level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced']),
            'category_id' => Category::factory(), // Assurez-vous que CategoryFactory existe
        ];
    }
}
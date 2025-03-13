<?php

use App\Models\Course;
use App\Models\Tag;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

test("can create a course", function () {
    
    $tags = Tag::factory()->count(2)->create();
    $category = Category::factory()->create();

  
    $courseData = [
        'title' => 'Test Course',
        'description' => 'This is a test course',
        'content' => 'Test content',
        'video' => 'test-video.mp4',
        'cover' => 'test-cover.jpg',
        'duration' => 120,
        'level' => 'beginner',
        'category_id' => $category->id,
        'tag_ids' => $tags->pluck('id')->toArray(), 
    ];

    
    $response = $this->post('api/courses', $courseData);
    $response->assertStatus(201); 
    $response->assertJson([
        'data' => [
            'title' => 'Test Course',
            'description' => 'This is a test course',
            'content' => 'Test content',
            'video' => 'test-video.mp4',
            'cover' => 'test-cover.jpg',
            'duration' => 120,
            'level' => 'beginner',
            'category_id' => $category->id,
            'category' => $category->name,
            'tags' => $tags->pluck('name')->toArray(),
        ],
    ]);

   
    $this->assertDatabaseHas('courses', [
        'title' => 'Test Course',
        'category_id' => $category->id,
    ]);
    $course = Course::first();
    $this->assertCount(2, $course->tags);
});

test("can show a specific course", function () {
   
    $course = Course::factory()->create();

    
    $response = $this->get("api/courses/{$course->id}");
    $response->assertStatus(200);

    
    $response->assertJson([
        'data' => [
            'id' => $course->id,
            'title' => $course->title,
            'description' => $course->description,
            'content' => $course->content,
            'video' => $course->video,
            'cover' => $course->cover,
            'duration' => $course->duration,
            'level' => $course->level,
            'category_id' => $course->category_id,
            'category' => $course->category->name,
            'tags' => $course->tags->pluck('name')->toArray(),
        ],
    ]);
});

test("can update a course", function () {
 
    $course = Course::factory()->create();
    $tags = Tag::factory()->count(2)->create();
    $course->tags()->attach($tags);
    $updatedData = [
        'title' => 'Updated Course Title',
        'description' => 'Updated description',
        'content' => 'Updated content',
        'video' => 'updated-video.mp4',
        'cover' => 'updated-cover.jpg',
        'duration' => 180, 
        'level' => 'intermediate',
        'category_id' => $course->category_id,
        'tag_ids' => $tags->pluck('id')->toArray(), 
    ];

 
    $response = $this->put("api/courses/{$course->id}", $updatedData);
    $response->assertStatus(200);

    
    $response->assertJson([
        'success' => true,
        'data' => 'Course Updated Successfully',
    ]);


    $this->assertDatabaseHas('courses', [
        'id' => $course->id,
        'title' => 'Updated Course Title',
        'description' => 'Updated description',
        'content' => 'Updated content',
        'video' => 'updated-video.mp4',
        'cover' => 'updated-cover.jpg',
        'duration' => 180,
        'level' => 'intermediate',
        'category_id' => $course->category_id,
    ]);

    
    $updatedCourse = Course::find($course->id);
    $this->assertCount(2, $updatedCourse->tags);
});


test("can delete a course", function () {
   
    $course = Course::factory()->create();


    $response = $this->delete("api/courses/{$course->id}");
    $response->assertStatus(204); 

   
    $this->assertDatabaseMissing('courses', [
        'id' => $course->id,
    ]);
});
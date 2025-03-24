<?php
use App\Models\Video;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

test("can create a video", function () {

    $course = Course::factory()->create();

 
    $videoData = [
        'title' => 'Test Video',
        'description' => 'This is a test video',
        'url' => 'http://example.com/video.mp4',
        'course_id' => $course->id,
    ];

    
    $response = $this->postJson("api/courses/{$course->id}/videos", $videoData);

    $response->assertStatus(201);
    $response->assertJson([
        'message' => 'Video created successfully',
        'video' => [
            'title' => 'Test Video',
            'description' => 'This is a test video',
            'url' => 'http://example.com/video.mp4',
            'course_id' => $course->id,
        ],
    ]);

    
    $this->assertDatabaseHas('videos', [
        'title' => 'Test Video',
        'course_id' => $course->id,
    ]);
});

test("can get videos for a course", function () {

    $course = Course::factory()->create();
    $videos = Video::factory()->count(3)->create(['course_id' => $course->id]);

  
    $response = $this->getJson("api/courses/{$course->id}/videos");

   
    $response->assertStatus(200);
    $response->assertJsonCount(3, 'videos');
    $response->assertJsonStructure([
        'videos' => [
            '*' => [
                'id',
                'title',
                'description',
                'url',
                'course_id',
            ],
        ],
    ]);
});

test("can get a specific video", function () {

    $video = Video::factory()->create();

    
    $response = $this->getJson("api/videos/{$video->id}");

    
    $response->assertStatus(200);
    $response->assertJson([
        'video' => [
            'id' => $video->id,
            'title' => $video->title,
            'description' => $video->description,
            'url' => $video->url,
            'course_id' => $video->course_id,
        ],
    ]);
});


test("can update a video", function () {

    $video = Video::factory()->create();

    
    $updatedData = [
        'title' => 'Updated Video Title',
        'description' => 'Updated description',
        'url' => 'http://example.com/updated-video.mp4',
    ];

  
    $response = $this->putJson("api/videos/{$video->id}", $updatedData);

    
    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Video updated successfully',
        'video' => [
            'id' => $video->id,
            'title' => 'Updated Video Title',
            'description' => 'Updated description',
            'url' => 'http://example.com/updated-video.mp4',
            'course_id' => $video->course_id,
        ],
    ]);

    
    $this->assertDatabaseHas('videos', [
        'id' => $video->id,
        'title' => 'Updated Video Title',
        'description' => 'Updated description',
        'url' => 'http://example.com/updated-video.mp4',
    ]);
});


test("can delete a video", function () {

    $video = Video::factory()->create();

  
    $response = $this->deleteJson("api/videos/{$video->id}");

    
    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Video deleted successfully',
    ]);

    $this->assertDatabaseMissing('videos', [
        'id' => $video->id,
    ]);
});
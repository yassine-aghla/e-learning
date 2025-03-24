<?php
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

test("can enroll a user in a course", function () {
  
    $user = User::factory()->create();
    $course = Course::factory()->create();

 
    $response = $this->actingAs($user)->postJson("api/courses/{$course->id}/enroll");

    
    $response->assertStatus(201);
    $response->assertJson([
        'message' => 'Enrollment successful',
        'enrollment' => [
            'course_id' => $course->id,
            'user_id' => $user->id,
            'status' => 'pending',
        ],
    ]);


    $this->assertDatabaseHas('enrollments', [
        'course_id' => $course->id,
        'user_id' => $user->id,
        'status' => 'pending',
    ]);
});

test("cannot enroll a user twice in the same course", function () {
    
    $user = User::factory()->create();
    $course = Course::factory()->create();

   
    $this->actingAs($user)->postJson("api/courses/{$course->id}/enroll");

    
    $response = $this->actingAs($user)->postJson("api/courses/{$course->id}/enroll");

    $response->assertStatus(400);
    $response->assertJson([
        'message' => 'User is already enrolled',
    ]);
});

test("can get enrollments for a course", function () {
 
    $course = Course::factory()->create();
    $enrollments = Enrollment::factory()->count(3)->create(['course_id' => $course->id]);

    $response = $this->getJson("api/courses/{$course->id}/enrollments");

    
    $response->assertStatus(200);
    $response->assertJsonCount(3, 'enrollments');
    $response->assertJsonStructure([
        'enrollments' => [
            '*' => [
                'id',
                'course_id',
                'user_id',
                'status',
            ],
        ],
    ]);
});

test("can update enrollment status", function () {
    
    $enrollment = Enrollment::factory()->create(['status' => 'pending']);

    $response = $this->putJson("api/enrollments/{$enrollment->id}", [
        'status' => 'accepted',
    ]);

 
    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Enrollment status updated',
        'enrollment' => [
            'id' => $enrollment->id,
            'status' => 'accepted',
        ],
    ]);


    $this->assertDatabaseHas('enrollments', [
        'id' => $enrollment->id,
        'status' => 'accepted',
    ]);
});

test("can delete an enrollment", function () {
 
    $enrollment = Enrollment::factory()->create();

   
    $response = $this->deleteJson("api/enrollments/{$enrollment->id}");

   
    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Enrollment deleted',
    ]);

    
    $this->assertDatabaseMissing('enrollments', [
        'id' => $enrollment->id,
    ]);
});
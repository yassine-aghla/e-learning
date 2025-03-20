<?php

namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\Badge;
use Illuminate\Http\Request;

class StudentController extends Controller
{
   
    public function getStudentCourses($id)
    {
        
        $enrollments = Enrollment::where('user_id', $id)->with('course')->get();

        $courses = $enrollments->pluck('course');

        return response()->json(['courses' => $courses]);
    }

   
    public function getStudentProgress($id)
    {
       
        $enrollments = Enrollment::where('user_id', $id)->with('course')->get();


        $progress = $enrollments->map(function ($enrollment) {
            return [
                'course_id' => $enrollment->course_id,
                'course_title' => $enrollment->course->title,
                'progress' => $enrollment->progress, 
            ];
        });

        return response()->json(['progress' => $progress]);
    }

   
}
<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class MentorController extends Controller
{
   
    public function getMentorCourses($id)
    {
        $courses = Course::where('user_id', $id)->get();
        return response()->json(['courses' => $courses]);
    }

    
    public function getMentorStudents($id)
    {
        
        $courses = Course::where('user_id', $id)->pluck('id');

       
        $enrollments = Enrollment::whereIn('course_id', $courses)->with('user')->get();

        
        $students = $enrollments->pluck('user')->unique();

        return response()->json(['students' => $students]);
    }

    
    public function getMentorPerformance($id)
    {
       
        $courses = Course::where('user_id', $id)->pluck('id');

       
        $totalCourses = $courses->count();
        $totalEnrollments = Enrollment::whereIn('course_id', $courses)->count();
        $totalStudents = Enrollment::whereIn('course_id', $courses)->distinct('user_id')->count('user_id');

        return response()->json([
            'total_courses' => $totalCourses,
            'total_enrollments' => $totalEnrollments,
            'total_students' => $totalStudents,
        ]);
    }

    public function search(Request $request)
    {
      
        $query = $request->query('search');
        
       
        $mentorRole = Role::where('name', 'mentor')->first();
        
        if (!$mentorRole) {
            return response()->json([
                'success' => false,
                'message' => 'Le rôle mentor n\'existe pas dans le système'
            ], 404);
        }
        
        $mentors = User::role($mentorRole)
                      ->where(function($q) use ($query) {
                          $q->where('name', 'like', '%'.$query.'%')
                            // ->orWhere('expertise', 'like', '%'.$query.'%')
                            ->orWhere('bio', 'like', '%'.$query.'%');
                      })
                      ->with('roles:name')
                      ->select('id', 'name', 'email', 'bio')
                      ->get();
        
        return response()->json([
            'success' => true,
            'mentors' => $mentors
        ]);
    }

}

<?php
namespace App\Repositories;

use App\Models\Course;
use App\Interfaces\CourseRepositoryInterface;

class CourseRepository implements CourseRepositoryInterface
{
    public function index()
    {
        return Course::all();
    }

    public function getById($id)
    {
        return Course::findOrFail($id);
    }

    public function store(array $data)
    {
        return Course::create($data);
    }

    public function update(array $data, $id)
    {
        $course = Course::findOrFail($id);
        $course->update($data);
        return $course;
    }

    public function delete($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
    }

    public function search($query)
{
    return Course::where('title', 'like', '%'.$query.'%')
                ->orWhere('description', 'like', '%'.$query.'%')
                ->get();
}

public function filterByCategoryAndLevel($categoryName, $level)
{
    $query = Course::query()->with('category'); 
    
    if ($categoryName) {
        $query->whereHas('category', function($q) use ($categoryName) {
            $q->where('name', 'like', '%'.$categoryName.'%');
        });
    }
    
    if ($level) {
        $query->where('level', $level);
    }
    
    return $query->get();
}

}
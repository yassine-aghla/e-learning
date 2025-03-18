<?php
namespace App\Repositories;

use App\Models\Course;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Enrollment;
use App\Interfaces\StatsRepositoryInterface;

class StatsRepository implements StatsRepositoryInterface
{
    public function getCourseStats()
    {
        $totalCourses = Course::count();
        $publishedCourses = Course::where('status', 'accepted')->count();
        $draftCourses = Course::where('status', 'rejected')->count();

        return [
            'total_courses' => $totalCourses,
            'published_courses' => $publishedCourses,
            'draft_courses' => $draftCourses,
        ];
    }

    public function getCategoryStats()
    {
        $totalCategories = Category::count();
        $categoriesWithCourses = Category::has('courses')->count();

        return [
            'total_categories' => $totalCategories,
            'categories_with_courses' => $categoriesWithCourses,
        ];
    }

    public function getTagStats()
    {
        $totalTags = Tag::count();
        $tagsWithCourses = Tag::has('courses')->count();

        return [
            'total_tags' => $totalTags,
            'tags_with_courses' => $tagsWithCourses,
        ];
    }

    public function getEnrollmentStats()
    {
        $totalEnrollments = Enrollment::count();
        $pendingEnrollments = Enrollment::where('status', 'pending')->count();
        $acceptedEnrollments = Enrollment::where('status', 'accepted')->count();
        $rejectedEnrollments = Enrollment::where('status', 'rejected')->count();

        return [
            'total_enrollments' => $totalEnrollments,
            'pending_enrollments' => $pendingEnrollments,
            'accepted_enrollments' => $acceptedEnrollments,
            'rejected_enrollments' => $rejectedEnrollments,
        ];
    }
}
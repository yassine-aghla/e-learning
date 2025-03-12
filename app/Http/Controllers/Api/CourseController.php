<?php
namespace App\Http\Controllers\Api;

use App\Models\Course;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Interfaces\CourseRepositoryInterface;
use App\Classes\ApiResponseClass;
use App\Http\Resources\CourseResource;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    private CourseRepositoryInterface $courseRepositoryInterface;

    public function __construct(CourseRepositoryInterface $courseRepositoryInterface)
    {
        $this->courseRepositoryInterface = $courseRepositoryInterface;
    }


    public function store(StoreCourseRequest $request)
    {
        $details = [
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content,
            'video' => $request->video,
            'cover' => $request->cover,
            'duration' => $request->duration,
            'level' => $request->level,
            'category_id' => $request->category_id,
        ];

        DB::beginTransaction();
        try {
            $course = $this->courseRepositoryInterface->store($details);
            if ($request->has('tag_ids')) {
                $course->tags()->attach($request->tag_ids);
            }
            DB::commit();
            return ApiResponseClass::sendResponse(new CourseResource($course), 'Course Created Successfully', 201);
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }

    public function show($id)
    {
        $course = $this->courseRepositoryInterface->getById($id);
        return ApiResponseClass::sendResponse(new CourseResource($course), '', 200);
    }

    public function update(UpdateCourseRequest $request, $id)
    {
        $updateDetails = [
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content,
            'video' => $request->video,
            'cover' => $request->cover,
            'duration' => $request->duration,
            'level' => $request->level,
            'category_id' => $request->category_id,
        ];

        DB::beginTransaction();
        try {
            $course = $this->courseRepositoryInterface->update($updateDetails, $id);
            if ($request->has('tag_ids')) {
                $course->tags()->sync($request->tag_ids);
            }
            DB::commit();
            return ApiResponseClass::sendResponse('Course Updated Successfully', '', 200);
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }

    public function destroy($id)
    {
        $this->courseRepositoryInterface->delete($id);
        return ApiResponseClass::sendResponse('Course Deleted Successfully', '', 204);
    }
}
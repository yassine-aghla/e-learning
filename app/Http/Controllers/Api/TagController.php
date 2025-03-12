<?php
namespace App\Http\Controllers\Api;

use App\Models\Tag;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Interfaces\TagRepositoryInterface;
use App\Classes\ApiResponseClass;
use App\Http\Resources\TagResource;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    private TagRepositoryInterface $tagRepositoryInterface;

    public function __construct(TagRepositoryInterface $tagRepositoryInterface)
    {
        $this->tagRepositoryInterface = $tagRepositoryInterface;
    }

    public function index()
    {
        $data = $this->tagRepositoryInterface->index();
        return ApiResponseClass::sendResponse(TagResource::collection($data), '', 200);
    }

    public function store(StoreTagRequest $request)
{
    $tagsData = $request->tags; // Récupère les tags sous forme de tableau

    DB::beginTransaction();
    try {
        $tags = [];
        foreach ($tagsData as $tag) {
            $tags[] = [
                'name' => $tag['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insertion en masse
        Tag::insert($tags);

        DB::commit();
        return ApiResponseClass::sendResponse('Tags Created Successfully', '', 201);
    } catch (\Exception $ex) {
        DB::rollback();
        return ApiResponseClass::sendResponse('Error while inserting tags', $ex->getMessage(), 500);
    }
}

    public function show($id)
    {
        $tag = $this->tagRepositoryInterface->getById($id);
        return ApiResponseClass::sendResponse(new TagResource($tag), '', 200);
    }

    public function update(UpdateTagRequest $request, $id)
    {
        $updateDetails = [
            'name' => $request->name,
        ];

        DB::beginTransaction();
        try {
            $tag = $this->tagRepositoryInterface->update($updateDetails, $id);
            DB::commit();
            return ApiResponseClass::sendResponse('Tag Updated Successfully', '', 200);
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }

    public function destroy($id)
    {
        $this->tagRepositoryInterface->delete($id);
        return ApiResponseClass::sendResponse('Tag Deleted Successfully', '', 204);
    }
}
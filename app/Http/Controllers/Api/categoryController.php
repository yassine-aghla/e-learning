<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Interfaces\CategoryRepositoryInterface;
use App\Classes\ApiResponseClass;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\DB;
class categoryController extends Controller
{
    
    private CategoryRepositoryInterface $CategoryRepositoryInterface;
    
    public function __construct(CategoryRepositoryInterface $CategoryRepositoryInterface)
    {
        $this->CategoryRepositoryInterface = $CategoryRepositoryInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->CategoryRepositoryInterface->index();

        return ApiResponseClass::sendResponse(CategoryResource::collection($data),'',200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $details =[
            'name' => $request->name,
            'categorie_id' => $request->categorie_id
        ];
        DB::beginTransaction();
        try{
            $categorie = $this->CategoryRepositoryInterface->store($details);

             DB::commit();
             return ApiResponseClass::sendResponse(new CategoryResource($categorie),'Product Create Successful',201);

        }catch(\Exception $ex){
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $categorie= $this->CategoryRepositoryInterface->getById($id);

        return ApiResponseClass::sendResponse(new CategorieResource($categorie),'',200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $categorie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $updateDetails =[
            'name' => $request->name,
            'categorie_id' => $request->categorie_id
        ];
        DB::beginTransaction();
        try{
            $categorie = $this->CategoryRepositoryInterface->update($updateDetails,$id);

             DB::commit();
             return ApiResponseClass::sendResponse('Product Update Successful','',201);

        }catch(\Exception $ex){
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
         $this->CategoryRepositoryInterface->delete($id);

        return ApiResponseClass::sendResponse('Product Delete Successful','',204);
    }
}
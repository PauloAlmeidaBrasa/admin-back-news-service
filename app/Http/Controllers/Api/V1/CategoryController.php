<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Services\CategoryService;
use App\Http\Controllers\Api\V1\BaseController;
use Illuminate\Http\JsonResponse;


class CategoryController extends BaseController
{

    protected $categoryRequest;

    protected $categoryService;

    function __construct(categoryRequest $categoryReq, CategoryService $categoryServ)
    {
        $this->categoryRequest = $categoryReq;
        $this->categoryService = $categoryServ;
    }



/**
 * @OA\Post(
 *     path="/api/v1/category/add-category",
 *     summary="Add a new category",
 *     description="Create a new category. Requires JWT authentication.",
 *     tags={"add-category"},
 *     security={{"bearerAuth": {}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title", "category"},
 *             @OA\Property(property="title", type="string", example="category 1", description="category title"),
 *             @OA\Property(property="category", type="integer", example=2, description="category ID")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="✅ Success",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="category", type="string", example="categoryAdded")
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - missing or invalid token"
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 */


    public function store(Request $request)
    {
        // Create category
        $payload = auth()->payload();

        $categoryData = [
            'title'      => $this->categoryRequest->input("title"),
            'subtitle'     => $this->categoryRequest->input("subtitle"),
            'text'  => $this->categoryRequest->input("text"),
            'category' => $this->categoryRequest->input('category'),
            'client_id' => $payload["client_id"]
        ];

        $result = $this->categoryService->create($categoryData);

        return match ($result['code']) {
            'SUCCESS' => $result['message'],
            'INTERROR' => response()->json($result, 500),
        };

    }



   
/**
 * 
 * @OA\Get(
 *     path="/api/v1/category/get-category",
 *     summary="Get all registered category who belong to the same client_id of the requester",
 *     tags={"get-category"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="List of category",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(
 *                     property="category",
 *                     type="array",
 *                     @OA\Items(
 *                         @OA\Property(property="title", type="string", example="category 1"),
 *                         @OA\Property(property="subtitle", type="string", example="subtitle 1"),
 *                         @OA\Property(property="text", type="string", example="text 1"),
 *                         @OA\Property(property="category", type="int", example="1")
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */

    public function categoryByClientId(){
        $payload = auth()->payload();
        $client =  $payload->get('client_id');

        $categoryID = null;
        if($this->categoryRequest->input("category_ID")){
            $categoryID = $this->categoryRequest->input("category_ID");
        }

        $result = $this->categoryService->categoryByClientId($client,$categoryID);


        return match ($result['code']) {
            'SUCCESS' => $this->respondWithSuccessList($result['data'],'category'),
            'INTERROR' => response()->json($result, 500),
        };


    }


    /**
 * @OA\Patch(
 *     path="/api/v1/category/update",
 *     summary="Update category data",
 *     tags={"Update categorys"},
 *     security={{"bearerAuth": {}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"category_ID"},
 *             @OA\Property(
 *                 property="category_ID",
 *                 type="integer",
 *                 example=123,
 *                 description="category ID to update"
 *             ),
 *             @OA\Property(
 *                 property="name",
 *                 type="string",
 *                 example="John Doe",
 *                 description="Optional new category name"
 *             ),
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 format="email",
 *                 example="john.doe@example.com",
 *                 description="Optional new category email"
 *             ),
 *             @OA\Property(
 *                 property="password",
 *                 type="string",
 *                 example="categoryecurePassword123",
 *                 description="Optional new password"
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="category updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="message", type="string", example="category ID 123 updated successfully")
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="category service unavailable")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */

    public function update(Request $request, category $category)
    {
        $categoryFieldsToUpdate = $this->categoryRequest->input();


        $result = $this->categoryService->update($categoryFieldsToUpdate);

        return match ($result['code']) {
            'SUCCESS'  => response()->json($result, 200),
            'NOTFOUND' => response()->json($result, 404),
            'INTERROR' => response()->json($result, 500),
        };

    }

    /**
 * @OA\Post(
 *     path="/api/v1/category/delete",
 *     summary="Delete a category by ID",
 *     description="Send the category_ID in the request body to delete the specified category.",
 *     tags={"Delete"},
 *     security={{"bearerAuth": {}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"category_ID"},
 *             @OA\Property(property="category_ID", type="integer", example=123, description="category's ID")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="✅ Success",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="message", type="string", example="category ID 123 removed")
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - missing or invalid token"
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="category service unavailable")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=403,
 *         description="category not found or the category you are trying to delete doesnt belong to your same client",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="category not found or the category you are trying to delete doesnt belong to your same client")
 *         )
 *     )
 * )
 */

    public function delete(): JsonResponse{

        $userID = $this->categoryRequest->input('category_ID');

        $result = $this->categoryService->delete($userID);

        return match ($result['code']) {
            'SUCCESS'  => response()->json($result, 200),
            'NOTFOUND' => response()->json($result, 404),
            'INTERROR' => response()->json($result, 500),
        };


    }
}

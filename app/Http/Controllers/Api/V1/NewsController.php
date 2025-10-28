<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use App\Http\Requests\NewsRequest;
use App\Services\NewsService;
use App\Http\Controllers\Api\V1\BaseController;

class NewsController extends BaseController
{

    protected $newsRequest;

    protected $newsService;

    function __construct(NewsRequest $newsReq, NewsService $newsServ)
    {
        $this->newsRequest = $newsReq;
        $this->newsService = $newsServ;
    }



/**
 * @OA\Post(
 *     path="/api/v1/news/add-news",
 *     summary="Add a new news",
 *     description="Create a new news. Requires JWT authentication.",
 *     tags={"add-news"},
 *     security={{"bearerAuth": {}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title", "category"},
 *             @OA\Property(property="title", type="string", example="news 1", description="news title"),
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
 *                 @OA\Property(property="news", type="string", example="newsAdded")
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
        // Create news
        $payload = auth()->payload();

        $newsData = [
            'title'      => $this->newsRequest->input("title"),
            'subtitle'     => $this->newsRequest->input("subtitle"),
            'text'  => $this->newsRequest->input("text"),
            'category' => $payload->get('category')
        ];

        $result = $this->newsService->create($newsData);

        return match ($result['code']) {
            'SUCCESS' => $result['message'],
            'INTERROR' => response()->json($result, 500),
        };

    }



   
/**
 * 
 * @OA\Get(
 *     path="/api/v1/news/get-news",
 *     summary="Get all registered news who belong to the same client_id of the requester",
 *     tags={"get-news"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="List of news",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(
 *                     property="news",
 *                     type="array",
 *                     @OA\Items(
 *                         @OA\Property(property="title", type="string", example="news 1"),
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

    public function newsByClientId(){

        $payload = auth()->payload();
        $client =  $payload->get('client_id');

        $newsID = null;
        if($this->newsRequest->input("news_ID")){
            $newsID = $this->newsRequest->input("news_ID");
        }

        $result = $this->newsService->newsByClientId($client,$newsID);


        return match ($result['code']) {
            'SUCCESS' => $this->respondWithSuccessList($result['data'],'news'),
            'INTERROR' => response()->json($result, 500),
        };


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        //
    }
}

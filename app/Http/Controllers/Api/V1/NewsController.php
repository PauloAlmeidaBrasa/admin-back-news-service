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
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */

    /**

 * 
 * @OA\Get(
 *     path="/api/v1/news/get-news",
 *     summary="Get all registered news who belong to the same client_id of the requester",
 *     tags={"get-users"},
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
 *                         @OA\Property(property="subtitle", type="string", example="subtitle 1")
 *                         @OA\Property(property="text", type="string", example="text 1")
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

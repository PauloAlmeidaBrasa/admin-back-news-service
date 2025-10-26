<?php

// namespace App\Http\Controllers;
namespace App\Http\Controllers\Api\V1;


use App\Services\UserService;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Api\V1\BaseController;
use Illuminate\Http\JsonResponse;




class UserController extends BaseController {

    protected $userRequest;

    protected $userService;

    function __construct(UserRequest $userReq, UserService $userServ)
    {
        $this->userRequest = $userReq;
        $this->userService = $userServ;
    }

/**

 * 
 * @OA\Get(
 *     path="/api/v1/user/get-users",
 *     summary="Get all registered users who belong to the same client_id of the requester",
 *     tags={"get-users"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="List of users",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(
 *                     property="users",
 *                     type="array",
 *                     @OA\Items(
 *                         @OA\Property(property="name", type="string", example="Paulo"),
 *                         @OA\Property(property="email", type="string", example="paulo@example.com")
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

    public function getUsersByClientId(){

        $payload = auth()->payload();
        $client =  $payload->get('client_id');

        $result = $this->userService->allUsersByClientId($client);


        return match ($result['code']) {
            'SUCCESS' => $this->respondWithSuccessList($result['data'],'users'),
            'INTERROR' => response()->json($result, 500),
        };


    }

/**
 * @OA\Post(
 *     path="/api/v1/user/add-user",
 *     summary="Add a new user",
 *     description="Create a new user. Requires JWT authentication.",
 *     tags={"add-user"},
 *     security={{"bearerAuth": {}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password", "name"},
 *             @OA\Property(property="email", type="string", example="user@example.com", description="User’s email address"),
 *             @OA\Property(property="password", type="string", example="123456", description="User’s password"),
 *             @OA\Property(property="name", type="string", example="John Doe", description="User’s name")
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
 *                 @OA\Property(property="users", type="string", example="userAdded")
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


    public function store(){

        // Create user
        $payload = auth()->payload();

        $userData = [
            'name'      => $this->userRequest->input("name"),
            'email'     => $this->userRequest->input("email"),
            'password'  => $this->userRequest->input("password"),
            'client_id' => $payload->get('client_id')
        ];

        $result = $this->userService->create($userData);

        return match ($result['code']) {
            'SUCCESS' => $result['message'],
            'INTERROR' => response()->json($result, 500),
        };

    }

/**
 * @OA\Post(
 *     path="/api/v1/user/delete",
 *     summary="Delete a user by ID",
 *     description="Send the user_ID in the request body to delete the specified user.",
 *     tags={"Delete"},
 *     security={{"bearerAuth": {}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_ID"},
 *             @OA\Property(property="user_ID", type="integer", example=123, description="User's ID")
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
 *                 @OA\Property(property="message", type="string", example="User ID 123 removed")
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
 *             @OA\Property(property="message", type="string", example="user service unavailable")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=403,
 *         description="User not found or the user you are trying to delete doesnt belong to your same client",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="User not found or the user you are trying to delete doesnt belong to your same client")
 *         )
 *     )
 * )
 */

    public function delete(): JsonResponse{


        try {
            $userID = $this->userRequest->input('user_ID');

            $payload = auth()->payload();
            $requesterClientID =  $payload->get('client_id');
            $user = $this->userService->delete($userID,$requesterClientID);
            if(!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found or the user you are trying to delete, doesnt belong to the same client',
                ], 403);
            }

            $msg = "user $user->id removed! ";
            return $this->respondWithSuccess($msg);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'user service unavailable'
            ], 500);
        }

    }
}



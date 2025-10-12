<?php

// namespace App\Http\Controllers;
namespace App\Http\Controllers\Api\V1;


use App\Services\UserService;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Api\V1\BaseController;



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
 *     tags={"Users"},
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

        $result = array_map(function($user) {
            return (object)[
                'name' => $user["name"],
                'email' => $user["email"]
            ];
        },$result);


        return $this->respondWithSuccess($result,'users');
    }

/**
 * @OA\Post(
 *     path="/api/v1/add-user",
 *     summary="Add a new user",
 *     description="Create a new user. Requires JWT authentication.",
 *     tags={"User"},
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

        $user = $this->userService->create($userData);
        return $this->respondWithSuccess($user->name,'users');

    }
}



<?php

// namespace App\Http\Controllers;
namespace App\Http\Controllers\Api\V1;


use App\Services\UserService;
use App\Http\Requests\UserRequest;


class UserController extends Controller {

    protected $userRequest;

    protected $userService;

    function __construct(UserRequest $userReq, UserService $userServ)
    {
        $this->userRequest = $userReq;
        $this->userService = $userServ;
    }

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



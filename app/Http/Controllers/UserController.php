<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Log;


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



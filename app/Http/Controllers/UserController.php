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


        // $userName = $this->userRequest->input("name");
        // $userPass = $this->userRequest->input("password");
        // $userEmail = $this->userRequest->input("email");

        // Create user

        $userData = [
            'name'     => $this->userRequest->input("name"),
            'email'    => $this->userRequest->input("email"),
            'password' => $this->userRequest->input("password")
        ];

        $user = $this->userService->create($userData);
        // dd($user);
        return $this->respondWithSuccess($user->name,'users');

    }
}



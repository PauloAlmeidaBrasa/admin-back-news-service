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
        $clientId = $this->userRequest->query('client_id');

        $result = $this->userService->allUsersByClientId($clientId);
        return $this->respondWithSuccess($result,'users');
    }
}



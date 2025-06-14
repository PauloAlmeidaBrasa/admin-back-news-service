<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Log;


class UserController extends Controller {

    protected $userService;

    function __construct()
    {
        $this->userService = $authService;
    }

    public function index(){
        dd('2e');

        dd('123');
    }
}



<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private UserService $userService){}
    public function login(LoginRequest $request){

        return $this->userService->login($request->validated());
    }
}

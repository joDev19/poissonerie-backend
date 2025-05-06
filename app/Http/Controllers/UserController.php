<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeInfoRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private UserService $userService){

    }
    //
    public function changeInfo(ChangeInfoRequest $request){
        return $this->userService->changeInfos($request->all());
    }
    public function changePassword(ChangePasswordRequest $request){
        return $this->userService->changePassword($request->all());
    }
}

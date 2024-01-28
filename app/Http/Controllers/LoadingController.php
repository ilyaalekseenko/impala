<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;



class LoadingController extends Controller
{
    private $userService;

    public function __construct(
        UserService $userService,
    )
    {
        $this->userService = $userService;
    }

    public function main_loading()
    {
        $user=$this->userService->getRole();
        return view('front.main_loading')->with('auth_user',$user);
    }
}

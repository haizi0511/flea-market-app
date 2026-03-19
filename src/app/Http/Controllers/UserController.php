<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;


class UserController extends Controller
{
    public function register()
    {
        return view('register');
    }

        public function login()
    {
        return view('login');
    }

}

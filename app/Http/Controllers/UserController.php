<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repository\UserRepository;

class UserController extends Controller
{
    public function __construct(UserRepository $userRepository)
    {
        $this->userReposity = $userRepository;
    }

    public function login(Requests\User\LoginRequest $req)
    {
        $data = $req->all();

//        $user_data = User::where(array('phone' => $data['phone']))->first();

        $user_data = $this->userReposity->read(['where' => ['phone' => $data['phone']]], true);

        if(password_verify($data['password'], $user_data['password'])) {
            session('user.id', $user_data['id']);
            unset($user_data['password']);
            return success($user_data);
        }

        return fail(trans('auth.failed'));
    }
}

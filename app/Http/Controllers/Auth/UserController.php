<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function login(Requests\User\LoginRequest $req)
    {
        $data = $req->all();

        $user_data = User::where(array('phone' => $data['phone']))->first();

        if(password_verify($data['password'], $user_data->password)) {
            session('user.id', $user_data->id);
            unset($user_data->password);
            return success($user_data);
        }

        return fail(trans('auth.failed'));
    }
}

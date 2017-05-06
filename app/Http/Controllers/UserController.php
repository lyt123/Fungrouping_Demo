<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Repository\UserRepository;
use App\Services\SMSService;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('must_login', ['except' => ['login', 'sendMsg', 'addUser', 'logout']]);
    }

    public function login(Requests\User\LoginRequest $req)
    {
        $data = $req->all();

        $user_data = UserRepository::read(['where' => ['phone' => $data['phone']]], true);

        //要确保$user_data有值
        if ($user_data && UserRepository::validatePassword($data['password'], $user_data['password'])) {
            session()->put('user.id', $user_data['id']);
            unset($user_data['password']);

            return success($user_data);
        }

        return fail(trans('user.login_failed'));
    }

    public function logout()
    {
        session()->forget('user');
        return success();
    }

    public function sendMsg(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'phone' => 'required|digits:11'
        ]);
        $phone = $req->input('phone');

        if ($validator->fails()) {
            return fail($validator->errors());
        }

        if ($code = SMSService::sendMessage($phone)) {
            //记录当前时间戳以后续验证验证码是否超过设置的时间
            session()->put('register.send_time', time());
            //设置验证码session值
            session()->put('register.security_code', $code);

            session()->put('register.phone', $phone);
            return success();
        }

        return fail(trans('tip.send_message_fail'));
    }

    public function addUser(Requests\User\AddUserRequest $req)
    {
        $data = $req->only('code', 'password', 'username', 'sex');

        if (
        UserRepository::testCode($data['code'],
            session()->get('register.security_code'),
            session()->get('register.send_time'
            ))
        ) {

            $data['phone'] = session()->get('register.phone');
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            unset($data['code']);//必须unset
            session()->forget('register');
            $result = UserRepository::store($data);
            return success(['user_id' => $result->id]);
        } else {
            return fail(trans('tip.code_error'));
        }
    }

    public function uploadAvatar(Request $req)
    {
        $path = UploadService::upload($req);
        UserRepository::updateData(['head_path' => $path], ['id' => session()->get('user.id')]);
        return success();
    }

    public function updatePassword(Request $req)
    {
        Validator::make($req->all(), [
            'prepassword' => 'required|between:5,12|alpha_num',
            'newpassword' => 'required|between:5,12|alpha_num',
        ]);
        $data = $req->all();

        $user_data = UserRepository::read(['id' => session()->get('user.id')], true);

        if (
            password_verify($data['prepassword'], $user_data['password'])
        ) {
            UserRepository::updateData(array(
                'password' => password_hash($data['newpassword'], PASSWORD_DEFAULT)), ['id' => $user_data['id']]);
            return success();
        }
        return fail(trans('tip.origin_password_wrong'));
    }

    public function forgetPasswordSendMsg(Request $req)
    {
        Validator::make($req->all(), [
            'prepassword' => 'required|between:5,12|alpha_num',
            'newpassword' => 'required|between:5,12|alpha_num',
        ]);
        $data = $req->only('phone');

        if (
        UserRepository::read([
            'where' => [
                'phone' => $data['phone'], 'id' => session()->get('user.id')
            ]])
        ) {
            if ($code = SMSService::sendMessage($data['phone'])) {
                session()->put('forget_password.security_code', $code);
                session()->put('forget_password.phone', $data['phone']);
                session()->put('forget_password.send_time', time());
                return success();
            }
        }

        return fail(trans('tip.forget_password_send_message_fail'));
    }

    /**
     * Des :
     * Auth:lyt123
     */
    public function forgetPasswordCheckCode(Request $req)
    {
        $data = $req->only('code');

        if (
        UserRepository::testCode($data['code'],
            session()->get('forget_password.security_code'),
            session()->get('forget_password.send_time')
        )
        ) {
            session()->put('forget_password.check_code', true);
            return success();
        }

        return fail(trans('tip.not_check_code'));
    }

    public function forgetPasswordNewPassword(Request $req)
    {
        Validator::make($req->all(), [
            'password' => 'required|between:5,12|alpha_num',
        ]);

        $data = $req->only('password');

        if (session()->get('forget_password.check_code')) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            UserRepository::updateData($data, ['id' => session()->get('user.id')]);
            session()->forget('forget_password');
            return success();
        }

        return fail('tip.code_error');
    }
}

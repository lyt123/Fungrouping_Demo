<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models;
use App\Repository\UserRepository;
use App\Services\SMSService;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('must_login', ['except' => ['login', 'sendMsg', 'addUser', 'logout', 'test']]);
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

    public function test()
    {
        //插入的时候userid会自动添加
//        $data_to_add = ['resident' => 'school'];
//        $user = Models\User::find(2157);
//        $data = $user->userintro()->create($data_to_add);

        //        $data =Models\User::find(2157)->userintro;
        //  "id": 4,
        //  "userid": 2157,
        //  "birth": "2016-11-07 00:00:00",
        //  "resident": "五邑大学玫瑰园",
        //  "profession": "好学生",
        //  "constellation": "摩羯座",
        //  "blood_group": "A",
        //  "self_intro": "志伟是"

        //从user_intro表往user表插入数据，应该不行，也没遇到过这种需求
        //                $data_to_add = ['username' => 'ivanlin'];
        //        $user_intro = Models\UserIntro::find(7);
        //                $data = $user_intro->user()->create($data_to_add);

        //        $data = UserIntro::find(4)->user;
        //  "id": 2157,
        //  "phone": "15875064665",
        //  "password": "$2y$10$s2aYK5yjMHy9VBoXHP3v0u1m5sr5HClYgVkEuPvcgogJ5ueVE.tDm",
        //  "username": "何志伟",
        //  "head_path": "Public/user/2157/user_head/2157-58115a24b18b5.jpg",
        //  "sex": "m"

        //插入的时候userid会自动添加
        //                $data_to_add = [['title' => 'school']];
        //                $user = Models\User::find(2157);
        //                $data = $user->teams()->create($data_to_add);

        //        $data = User::find(2158)->teams;
        //        {
        //            "id": 37,
        //            "title": "运动",
        //            "intro": "明天下午三点半\n我们在江门体育馆一起运动吧\n来打羽毛球，篮球……什么都行\n来放松下呗",
        //            "ctime": "2016-10-14 20:32:09",
        //            },
        //        {
        //            "id": 37,
        //            "title": "运动",
        //            "intro": "明天下午三点半\n我们在江门体育馆一起运动吧\n来打羽毛球，篮球……什么都行\n来放松下呗",
        //            "ctime": "2016-10-14 20:32:09",
        //            },

        //        $data = Team::find(37)->user;
        //        "id": 2158,
        //  "phone": "13719543701",
        //  "password": "$2y$10$786s6CCR4YFo81yNXmYhMejKQi.reu42bMANyiDlHGHl/B09TEBYG",
        //  "username": "吴秋婉",
        //  "head_path": "upload/img/user_head/21586g7aGAR2.jpg",
        //  "sex": "f"

        //往中间表插入数据
        //        $user = Models\User::find(2157);
        //        $team = Models\Team::find(61);
        //        $data = $user->user_team()->save($team, ['expect_score' => 30]);

//        $user = Models\User::find(2157);
//        $team = Models\Team::find(61);
//        $data = $user->user_team()->save($team, ['expect_score' => 30]);
//        $team->team_user()->attach(2157, ['expect_score' => 30]);

        //        $data = Models\User::find(2157)->user_team;
        //        {
        //            "id": 37,
        //            "title": "运动",
        //            "intro": "明天下午三点半\n我们在江门体育馆一起运动吧\n来打羽毛球，篮球……什么都行\n来放松下呗",
        //            "ctime": "2016-10-14 20:32:09",
        //        "pivot": {
        //        "user_id": 2157,
        //      "team_id": 40
        //    }
        //            },
        //        {
        //            "id": 37,
        //            "title": "运动",
        //            "intro": "明天下午三点半\n我们在江门体育馆一起运动吧\n来打羽毛球，篮球……什么都行\n来放松下呗",
        //            "ctime": "2016-10-14 20:32:09",
        //    "pivot": {
        //        "user_id": 2157,
        //      "team_id": 40
        //    }
        //            },

        //        $data = Models\Team::find(61)->team_user;
        //        $data = Models\Team::with('team_user')->find(61);
        //        $data = Models\Team::with(['team_user' => function ($query) {
        //            $query->select('username');
        //        }])->find(61);
        //        {
        //            "id": 2157,
        //    "phone": "15875064665",
        //    "password": "$2y$10$s2aYK5yjMHy9VBoXHP3v0u1m5sr5HClYgVkEuPvcgogJ5ueVE.tDm",
        //    "username": "何志伟",
        //    "head_path": "Public/user/2157/user_head/2157-58115a24b18b5.jpg",
        //    "sex": "m",
        //    "pivot": {
        //            "team_id": 61,
        //      "user_id": 2157
        //    }
        //  },
        //        {
        //            "id": 2158,
        //    "phone": "13719543701",
        //    "password": "$2y$10$786s6CCR4YFo81yNXmYhMejKQi.reu42bMANyiDlHGHl/B09TEBYG",
        //    "username": "吴秋婉",
        //    "head_path": "upload/img/user_head/21586g7aGAR2.jpg",
        //    "sex": "f",
        //    "pivot": {
        //            "team_id": 61,
        //      "user_id": 2158
        //    }
        f($data);
    }
}

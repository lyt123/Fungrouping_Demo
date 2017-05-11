<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Illuminate\Routing\Route as IlluminateRoute;
use App\Helper\CaseInsensitiveUriValidator;
use Illuminate\Routing\Matching\UriValidator;

$validators = IlluminateRoute::getValidators();
$validators[] = new CaseInsensitiveUriValidator;
IlluminateRoute::$validators = array_filter($validators, function($validator) {
    return get_class($validator) != UriValidator::class;
});

//测试
Route::get('/test', 'UserController@test');

//用户
Route::post('/Home/user/login', 'UserController@login');
Route::get('/Home/user/logout', 'UserController@logout');
Route::post('/Home/user/sendMsg', 'UserController@sendMsg');
Route::post('/Home/User/addUser', 'UserController@addUser');
Route::POST('/Home/user/uploadAvatar', 'UserController@uploadAvatar');
Route::POST('/Home/user/updatePassword', 'UserController@updatePassword');
Route::POST('/Home/user/forgetPasswordSendMsg', 'UserController@forgetPasswordSendMsg');
Route::POST('/Home/user/forgetPasswordCheckCode', 'UserController@forgetPasswordCheckCode');
Route::POST('/Home/user/forgetPasswordNewPassword', 'UserController@forgetPasswordNewPassword');

//公开活动
Route::post('Fungrouping/Home/team/team-picture', 'TeamController@uploadPicture');

Route::get('Fungrouping/Home/team/teamSelf', 'JoinTeamController@teamSelf');
Route::get('Fungrouping/Home/team/teamInvited', 'JoinTeamController@teamInvited');
Route::get('Fungrouping/Home/team/teamList', 'JoinTeamController@teamList');
Route::resource('Fungrouping/Home/team', 'TeamController');
Route::resource('Fungrouping/Home/team.join', 'JoinTeamController'/*, ['only'=>['store', 'update', 'destroy']]*/);

//不公开活动
Route::get('fungrouping/home/acts/self', 'ActController@myAct');
Route::get('fungrouping/home/acts/invited', 'ActController@actInvited');
Route::get('fungrouping/home/act/time/vote', 'ActController@teamVote');
Route::get('fungrouping/home/act/join/detail', 'ActController@joinDetail');
Route::get('fungrouping/home/act/qrcode', 'ActController@createActQRcode');
Route::post('fungrouping/home/act/response/first', 'ActController@response');
Route::post('fungrouping/home/act/response/again', 'ActController@reResponse');
Route::post('fungrouping/home/act/join', 'ActController@join');
Route::post('fungrouping/home/act/creater/response', 'ActController@createUserResponse');
Route::post('fungrouping/home/act/creater/reject', 'ActController@createrRejectJoin');
Route::resource('fungrouping/home/act', 'ActController');

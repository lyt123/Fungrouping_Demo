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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::post('/Home/user/login', 'UserController@login');
Route::get('/Home/user/logout', 'UserController@logout');
Route::post('/Home/user/sendMsg', 'UserController@sendMsg');
Route::post('/Home/User/addUser', 'UserController@addUser');
Route::POST('/Home/user/uploadAvatar', 'UserController@uploadAvatar');
Route::POST('/Home/user/updatePassword', 'UserController@updatePassword');
Route::POST('/Home/user/forgetPasswordSendMsg', 'UserController@forgetPasswordSendMsg');
Route::POST('/Home/user/forgetPasswordCheckCode', 'UserController@forgetPasswordCheckCode');
Route::POST('/Home/user/forgetPasswordNewPassword', 'UserController@forgetPasswordNewPassword');

Route::resource('Fungrouping/Home/team', 'TeamController');
Route::resource('Fungrouping/Home/joinTeam', 'TeamController');
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Headers:*');
header('Access-Control-Allow-Methods:*');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('auth/register', 'AuthController@register');

Route::resources([
    'threads' => 'ThreadController',
    'nodes' => 'NodeController',
    'banners' => 'BannerController',
    'tags' => 'TagController',
    'comments' => 'CommentController',
    'notifications' => 'NotificationController',
]);

Route::post('user/send-active-mail', 'UserController@sendActiveMail');
Route::get('user/activate', 'UserController@activate')->name('user.activate');
Route::get('user', 'UserController@me');
Route::get('user/{user}', 'UserController@index');
Route::get('user/{user}/followers', 'UserController@followers');
Route::get('user/{user}/followings', 'UserController@followings');
Route::patch('user/{user}', 'UserController@update');
Route::post('user/{user}/follow', 'UserController@follow');
Route::post('user/{user}/unfollow', 'UserController@unfollow');

Route::post('notifications/mark-all-as-read', 'NotificationController@markAllAsRead')
            ->name('notifications.mark_all_as_read');

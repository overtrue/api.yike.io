<?php

use Illuminate\Support\Facades\Route;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Content-Type, Access-Control-Allow-Headers, X-Requested-With');
header('Access-Control-Allow-Methods: *');

// Auth
Route::post('auth/register', 'AuthController@register');
Route::get('oauth/redirect-url/{platform}', 'OAuthController@getRedirectUrl');
Route::get('oauth/callback/{platform}', 'OAuthController@handleCallback');
Route::post('contents/preview', 'ContentController@preview');

\LaravelUploader::routes();

// Others
Route::get('threads/search', 'ThreadController@search')->name('threads.search');
Route::get('relations', 'RelationController@index')->name('relations.index');
Route::post('relations/{relation}', 'RelationController@toggleRelation')->name('relations.toggle');
Route::get('me', 'UserController@me');
Route::post('user/exists', 'UserController@exists');
Route::post('user/send-active-mail', 'UserController@sendActiveMail');
Route::post('user/reset-password', 'AuthController@reset');
Route::get('user/activate', 'UserController@activate')->name('user.activate');
Route::get('user/notifications', 'UserController@notifications');
Route::post('user/mail', 'UserController@editEmail');
Route::get('user/mail', 'UserController@updateEmail')->name('user.update-email');
Route::post('user/forget-password', 'AuthController@forgetPassword');

Route::get('user/{user}/followers', 'UserController@followers');
Route::get('user/{user}/followings', 'UserController@followings');
Route::get('user/{user}/activities', 'UserController@activities');

Route::get('nodes/{node}/threads', 'NodeController@threads');

Route::post('threads/{thread}/report', 'ThreadController@report');

Route::post('comments/{comment}/up-vote', 'CommentController@upVote');
Route::post('comments/{comment}/down-vote', 'CommentController@downVote');
Route::post('comments/{comment}/cancel-vote', 'CommentController@cancelVote');

Route::post('notifications/mark-all-as-read', 'NotificationController@markAllAsRead')
            ->name('notifications.mark_all_as_read');

// Resources
Route::resources([
    'threads' => 'ThreadController',
    'nodes' => 'NodeController',
    'banners' => 'BannerController',
    'tags' => 'TagController',
    'comments' => 'CommentController',
    'users' => 'UserController',
    'notifications' => 'NotificationController',
]);

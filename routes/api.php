<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resources([
    'threads' => 'ThreadController',
    'nodes' => 'NodeController',
    'banners' => 'BannerController',
    'tags' => 'TagController',
    'comments' => 'CommentController',
    'notifications' => 'NotificationController',
]);
Route::post('notifications/mark-all-as-read', 'NotificationController@markAllAsRead')
            ->name('notifications.mark_all_as_read');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

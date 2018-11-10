<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function index(Request $request)
    {
        return $request->user()->notifications()->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $id
     *
     * @return int
     */
    public function update(Request $request, string $id)
    {
        return $request->user()->unreadNotifications()->whereId($id)->firstOrFail()->markAsRead();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function markAllAsRead(Request $request)
    {
        return $request->user()->unreadNotifications->markAsRead();
    }
}

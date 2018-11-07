<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Http\Resources\BannerResource;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $banners = Banner::latest()->filter($request->all())->paginate($request->get('per_page', 20));

        return BannerResource::collection($banners);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Http\Resources\BannerResource
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Banner::class);

        $this->validate($request, [
        ]);

        return new BannerResource(Banner::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Banner $banner
     *
     * @return \App\Http\Resources\BannerResource
     */
    public function show(Banner $banner)
    {
        return new BannerResource($banner);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Banner              $banner
     *
     * @return \App\Http\Resources\BannerResource
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Banner $banner)
    {
        $this->authorize('update', Banner::class);

        $this->validate($request, [
            // validation rules...
        ]);

        $banner->update($request->all());

        return new BannerResource(Banner::create($request->all()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Banner $banner
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Banner $banner)
    {
        $this->authorize('delete', $banner);

        $banner->delete();

        return $this->withNoContent();
    }
}

<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Bookmark;

class BookmarksController extends Controller
{
    public function __construct()
    {
        // Authorization middleware (if the user does not have appropriate permissions, do a not currently logged in error)
        $this->middleware('auth:api')->except(['index', 'show']);
        $this->middleware('can:update,bookmark')->only('update');
        $this->middleware('can:create,bookmark')->only('store');
        $this->middleware('can:delete,bookmark')->only('destroy');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $bookmarks = [];

        // Check if their is a user designated in the api, and if so assign the user to $user
        if (auth()->guard('api')->check())
        {
            $user = auth()->guard('api')->user();
        }

        $bookmarks = Bookmark::getFilteredBookmarks($user);

        return response()->json(["success" => $bookmarks], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bookmark  $bookmark
     * @return \Illuminate\Http\Response
     */
    public function show(Bookmark $bookmark)
    {
        return $bookmark;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bookmark  $bookmark
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bookmark $bookmark)
    {
        $bookmark->update($request->all());
        return $bookmark;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bookmark  $bookmark
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bookmark $bookmark)
    {


        return 204;
    }
}

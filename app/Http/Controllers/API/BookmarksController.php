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
        $this->middleware('can:view,bookmark')->only('view');
        $this->middleware('can:create,App\Bookmark')->only('store');
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

        $bookmarks = Bookmark::getFilteredBookmarks($user)->toArray();

        // Keep consistency between paginated bookmark data and normal bookmark data
        if (!array_key_exists('data', $bookmarks)) {
            $data = $bookmarks;
            $bookmarks = [];
            $bookmarks['data'] = $data;
        }
        return response()->json($bookmarks, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'title' => ['required', 'min:3'],
            'url' => ['required', 'url'],
            'description' => ['min:3'],
        ]);

        $attributes['user_id'] = auth()->id();

        if ($bookmark = Bookmark::create($attributes)) {
            return response()->json([
                'success' => true,
                'data' => $bookmark->toArray(),
            ], 201);
        }
        return response()->json([
            'success' => false,
            'message' => 'User is not authorized to add a bookmark',
        ], 401);
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
        if (!$bookmark) {
            return response()->json([
                'success' => 'false',
                'message' => 'Bookmark could not be found',
            ], 400);
        }

        if ($bookmark->fill($request->all())->save()) {
            return response()->json([
                'success' => 'true',
                'message' => 'bookmark successfully updated'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Bookmark could not be updated'
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Bookmark  $bookmark
     * @return \Illuminate\Http\Response
     */
    public function destroy(?Bookmark $id)
    {
        if (!$bookmark) {
            return response()->json([
                'success' => false,
                'message' => 'Bookmark could not be found',
            ], 400);
        }

        if ($bookmark->delete()) {
            return response()->json([
                'success' => true,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Bookmark could not be deleted',
        ], 500);
    }

    /**
     *
     *
     * @param App\Bookmark $bookmark
     * @param string $tag_name
     * @return \Illuminate\Http\Response
     */
    public function attachTag(Bookmark $bookmark)
    {


    }

    /**
     *
     *
     * @param App\Bookmark $bookmark
     * @param App\Tag $tag
     * @return \Illuminate\Http\Response
     */
    public function detachTag(Bookmark $bookmark, Tag $tag_name)
    {


    }
}

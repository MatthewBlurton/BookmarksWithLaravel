<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;

use App\User;
use App\Bookmark;
use App\Tag;

class BookmarksController extends Controller
{
    public function __construct()
    {
        // Authorization middleware (if the user does not have appropriate permissions, do a not currently logged in error)
        $this->middleware('auth:api')->except(['index', 'show']);
        // $this->middleware('can:update,bookmark')->only('update');
        $this->middleware('can:view,bookmark')->only('view');
        // $this->middleware('can:create,\App\Bookmark')->only('store');
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

        $bookmark = new Bookmark();
        $bookmark->fill($attributes);
        $bookmark->user_id = auth()->user()->id;
        if ($request->has('is_puclic')) {
            $bookmark->is_public = true;
        }
        $bookmark->save();

        return response()->json([
            'success' => true,
            'data' => $bookmark->toArray(),
        ], 201);
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
        try {
            $request->validate([
                'title' => 'sometimes|required|min:3',
                'url' => 'sometimes|required|url',
                'description' => 'sometimes|required|min:3',
            ]);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 400);
        }

        if (!$bookmark) {
            return response()->json([
                'success' => 'false',
                'message' => 'Bookmark could not be found',
            ], 400);
        }

        if ($bookmark->update($request->all())) {
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
    public function destroy(Bookmark $bookmark)
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
     * Attaches a new tag to the selected bookmark
     *
     * @param Illuminate\Http\Request $request
     * @param App\Bookmark $bookmark
     * @return \Illuminate\Http\Response
     */
    public function attachTag(Request $request, Bookmark $bookmark)
    {
        try {
            $request->validate(['name' => 'required|min:3']);
        } catch (ValidationException $e) {
            return response()->json(['attached' => false, 'errors' => $e->errors()], 400);
        }

        $tag = $bookmark->attachTag($request->name);
        return response()->json(['attached' => true,
                'data' => $tag], 200);
    }

    /**
     * Detaches a tag from the selected bookmark
     *
     * @param App\Bookmark $bookmark
     * @param App\Tag $tag
     * @return \Illuminate\Http\Response
     */
    public function detachTag(Bookmark $bookmark, Tag $tag)
    {
        if (!$bookmark->tags->find($tag)) {
            return response()->json(['deatched' => false,
                    'errors' => 'Tag selected is not currently associated with the bookmark'], 409);
        }
        $bookmark->detachTag($tag);
        return response()->json(['detached' => true], 200);
    }
}

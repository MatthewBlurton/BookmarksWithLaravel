<?php

namespace App\Http\Controllers\API;

use App\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TagsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'can:delete,tag'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $tags = [];

        // Check if there is a logged in user in the api
        if (auth()->guard('api')->check()) {
            $user = auth()->guard('api')->user();
        }

        // Get a filtered set of tags based on the user's permissions and role
        $tags = Tag::getFilteredTags($user)->toArray();

        // Keep consistency between paginated tag data and normal tag data
        if (!array_key_exists('data', $tags)) {
            $data = $tags;
            $tags = [];
            $tags['data'] = $data;
        }
        return response()->json($tags, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        $user = auth()->user();

        // Check if there is a logged in user in the api
        if (auth()->guard('api')->check()) {
            $user = auth()->guard('api')->user();
        }

        // Get all the bookmarks related to the tag
        $bookmarks = $tag->getAssociatedFilteredBookmarks($user)->toArray();


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        if (!$tag) {
            return response()->json([
                'success' => false,
                'message' => 'Tag could not be found',
            ], 400);
        }

        if ($tag->delete()) {
            return response()->json(['success' => true,], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tag could not be deleted',
        ], 500);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostsRequest;
use App\Http\Resources\Post as PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use App\Events\PostHasRating;

class PostController extends Controller
{
    /**
     * Return the posts.
     */
    public function index(Request $request): ResourceCollection
    {
        return PostResource::collection(
            Post::search($request->input('q'))->withCount('comments')->latest()->paginate($request->input('limit', 20))
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostsRequest $request, Post $post): PostResource
    {
        $this->authorize('update', $post);

        $post->update($request->only(['title', 'content', 'posted_at', 'author_id', 'thumbnail_id', 'image']));

        return new PostResource($post);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostsRequest $request): PostResource
    {
        $this->authorize('store', Post::class);

        return new PostResource(
            Post::create($request->only(['title', 'content', 'posted_at', 'author_id', 'thumbnail_id']))
        );
    }

    /**
     * Return the specified resource.
     */
    public function show(Post $post): PostResource
    {
        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): JsonResponse
    {
        $this->authorize('delete', $post);

        $post->delete();

        return response()->noContent();
    }

    public function favoritePost(Post $post): String
    {
        Auth::user()->favorites()->attach($post->id); return $post->id;
    }

    /*
    * * Unfavorite a particular post * *
     *  @param Post $post *
     * @return Response */

    public function unFavoritePost(Post $post): String
    {
        Auth::user()->favorites()->detach($post->id); return $post->id;
    }

    public function updateRating(Request $request, Post $post)
    {
        return $post->upRating($request);    
    }

    public function destroyImage(Post $post): String
    {
        $post->deleteImage();
        $post->image = null;
        
        return $post->update(['image']);
    }

    public function destroyImagePreview(Post $post): String
    {
       $post->deleteImagePreview();
       $post->image_preview = null;

       return $post->update(['image_preview']);
    }
}

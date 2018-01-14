<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Like;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use Illuminate\Http\Request;

class BlogController extends Controller
{

  public function __construct()
    {
        $this->middleware('jwt.auth', ['only', [
          'createPost', 'likePost'
        ]]);
    }
  /**
     * Get posts for a user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllUserPosts(Request $request)
    {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user_id = $user->id;
        $posts = User::find($user_id)->blogPosts;
        return $posts;
    }

    /**
     * Create posts for a user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createPost(Request $request)
    {
        
        $this->validate($request, [
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);

        if (!$user = JWTAuth::parseToken()->authenticate()) {
          return response()->json(['message' => 'User not found'], 404);
        }

        $user_id = $user->id;
        $currentUser = User::where("id", $user_id)->first();
    
        $post = new Post([
          'title' => $request->input('title'),
          'content' => $request->input('content')
        ]);
        
        $currentUser->blogPosts()->save($post);

        return response()->json(['success' => true, 'The blog post has been created']);
    }

    /**
     * Edit a created blog post
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editPost(Request $request, $postId)
    {
        
        $this->validate($request, [
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);

        if (!$user = JWTAuth::parseToken()->authenticate()) {
          return response()->json(['message' => 'User not found'], 404);
        }

        $user_id = $user->id;

        $post = new Post();
        $editedPost = $post->where([
            ['user_id', '=', $user_id],
            ['id', '=', $postId],
        ])->update([
            'title' => $request->input('title'),
            'content' => $request->input('content')
        ]);

        return response()->json(['success' => true,
            'message' => 'The blog post has been created',
            'post' => $editedPost
        ]);
    }


    /**
     * Like a post
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function likePost(Request $request, $postId)
    {

        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        $user_id = $user->id; 
        
        $like = new Like();

        $likedPost = $like->where([
            ['user_id', '=', $user_id],
            ['post_id', '=', $postId],
        ])->first();

        if (is_null($likedPost) == false) {
            $like->where([
                ['user_id', '=', $user_id],
                ['post_id', '=', $postId],
            ])->delete();
            return response()->json([
                'success' => true,
                'like' => false,
                'user already liked this post, like deleted'], 200);
        }

        Like::create([
            'post_id' => $postId,
            'user_id' => $user_id
        ]);

        return response()->json([
            'success' => true,
            'like' => true,
            'This user liked this post'], 200);
    }

    /**
     * Get all likes for a post
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPostLikes(Request $request, $postId) {

        $post = Post::where("id", $postId)->first();
        $posts = $post->likes()->where('likes.post_id', $postId)->get();

        return response()->json(['message' => 'Success', 'post_likes' => $posts], 200);
       
    }
}

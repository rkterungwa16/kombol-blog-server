<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use Illuminate\Http\Request;

class BlogController extends Controller
{

  public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => 'getAllPosts']);
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

        $user = User::where("id", $user_id)->first();
        $userPost = $user->blogPosts()->where('user_id', $user_id)->get();
        return $userPost;
    }

    /**
     * Get a posts 
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOnePost(Request $request, $postId)
    {

        // try {
        //     $token = $this->auth->parseToken()->refresh();
        // } catch (JWTException $e) {
        //     throw new UnauthorizedHttpException('jwt-auth', $e->getMessage(), $e, $e->getCode());
        // }
        $post = Post::where("id", $postId)->first();   
        return response()->json(["success" => true, "post" => $post]);
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
          'content' => $request->input('content'),
          "author" => $currentUser->username
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
            'message' => 'The blog post has been edited'
        ]);
    }

    /**
     * Delete a created blog post
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deletePost(Request $request, $postId)
    {

        if (!$user = JWTAuth::parseToken()->authenticate()) {
          return response()->json(['message' => 'User not found'], 404);
        }

        $user_id = $user->id;

        $post = new Post();
        $postToDelete = $post->where([
            ['user_id', '=', $user_id],
            ['id', '=', $postId],
        ])->delete();

        return response()->json(['success' => true,
            'message' => 'The blog post has been deleted'
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
    public function getPostLikes(Request $request, $postId) 
    {

        $post = Post::where("id", $postId)->first();
        $likesOnAPost = $post->likes()->where('likes.post_id', $postId)->get();

        return response()->json(['message' => 'Success', 'post_likes' => $likesOnAPost], 200);
       
    }

    /**
     * Comment on a post
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function commentOnAPost(Request $request, $postId) 
    {

        $this->validate($request, [
            'comment' => 'required|min:5'
        ]);

        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        $user_id = $user->id; 
        
        $comment = new Comment();

        Comment::create([
            'post_id' => $postId,
            'user_id' => $user_id,
            'comment' => $request->input('comment'),
        ]);

        return response()->json(['success' => true,
            'message' => 'Comment has been created'
        ]);

    }

    /**
     * Get Comments on a post
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function getCommentsOnAPost(Request $request, $postId)
    {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(["message" => "User not found"], 404);
        }
    
        $user_id = $user->id; 
        $comments = Comment::where("post_id", "=", $postId)->get();
        $post = Post::where("id", $postId)->first();
        $userComments = $post->comments()->where('comments.post_id', $postId)->get();

        foreach ($comments as $key => $value) {
            $value["username"] = $userComments[$key]->username;
            $value["email"] = $userComments[$key]->email;
        }

        return response()->json(['message' => 'Success', 'post_comments' => $comments], 200);
    }

    /**
     * Get All posts created
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllPosts(Request $request)
    {
        $all_posts = Post::all();

        return response()->json(['message' => 'Success', 'all_posts' => $all_posts], 200);
    }

}

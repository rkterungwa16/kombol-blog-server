<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use App\Models\Following;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use DB;
use Hash;

/**
 * User controller creates user and actions that can be performed on users
 *
 * Some actions that can be performed on a user involves following
 * and unfollowing a user
 *
 * @category Null
 * @package  Blogging_Platform
 * @author   Richard Terungwa Kombol <richard.kombol@andela.com>
 * @license  Null MIT
 * @link     Null
 */
class UserController extends Controller
{
    /**
     * Calls the middleware to authenticate selected routes
     */
    public function __construct()
    {
        $this->middleware(
            'jwt.auth',
            ['only' =>
            [
            'getUser',
            'followUser',
            'getAllUserFollowers'
            ]]
        );
    }
    
    /**
     * API Register
     *
     * @param Request $request - request object
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $rules = [
            'username' => 'required|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ];

        $input = $request->only(
            'username',
            'email',
            'password',
            'password_confirmation'
        );

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            $error = $validator->messages()->toJson();
            return response()->json(['success'=> false, 'error'=> $error]);
        }

        $username = $request->username;
        $email = $request->email;
        $password = $request->password;
        $user = User::create(
            [
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password)]
        );

        return response()->json(
            [
            'success'=> true,
            'message'=> 'Thanks for signing up!']
        );
    }

    /**
     * API Login, on success return JWT Auth token
     *
     * @param Request $request - request object
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $input = $request->only('email', 'password');
        
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            $error = $validator->messages()->toJson();
            return response()->json(['success'=> false, 'error'=> $error]);
        }

        $credentials = $request->only('email', 'password');
        
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(
                    ['success' => false,
                    'error' => 'User does exist'],
                    401
                );
            }
        } catch (JWTException $e) {
            return response()->json(
                [
                'success' => false, 'error' => 'could_not_create_token'
                ],
                500
            );
        }
        return response()->json(
            [
                'success' => true,
                'data'=> [
                    'token' => $token,
                    'email' => $credentials['email'],
                    ]
            ]
        );
    }

    /**
     * Get current user
     *
     * @param Request $request - request object
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser(Request $request)
    {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(["message" => "User not found"], 404);
        }

        $user_id = $user->id;
        $user= User::where("id", "=", $user_id)->first();

        return response()->json(['success' => true, 'user' => $user]);
    }

    /**
     * Follow a user
     *
     * @param Request $request - request object
     * @param Request $userId  - user Id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function followUser(Request $request, $userId)
    {
        if (!$follower = JWTAuth::parseToken()->authenticate()) {
            return response()->json(["message" => "User not found"], 404);
        }

        $user_id = $follower->id;
        if ($user_id == $userId) {
            return response()->json(
                [
                "success" => false,
                "message" => "You can not follow yourself"
                ]
            );
        }
        
        $user= User::where("id", "=", $userId)->first();

        $follow = new Following();

        $followed = $follow->where(
            [
            ['user_id', '=', $userId],
            ['follower_id', '=', $user_id],
            ]
        )->first();

        if (is_null($followed) == false) {
            $follow->where(
                [
                ['user_id', '=', $userId],
                ['follower_id', '=', $user_id],
                ]
            )->delete();
            return response()->json(
                [
                'success' => false,
                'current user unfollowed target user'],
                200
            );
        }

        $following = Following::create(
            [
            "user_id" => $userId,
            "user_username" => $user->username,
            "user_email" => $user->email,
            "follower_id" => $user_id,
            "follower_email" => $follower->email,
            "follower_username" => $follower->username
            ]
        );
        
        return response()->json(
            [
            "success" => true,
            "Current user has successfully followed user"]
        );
    }

    /**
     * Get all of a user's followers
     *
     * @param Request $request - request object
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllUserFollowers(Request $request)
    {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(["message" => "User not found"], 404);
        }

        $user_id = $user->id;
        
        $userFollowers= Following::where("user_id", "=", $user_id)->get();

        return response()->json(
            [
            "success" => true,
            "followers" => $userFollowers
            ]
        );
    }

    /**
     * Get all users current user is following
     *
     * @param Request $request - request object
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllUserFollowing(Request $request)
    {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(["message" => "User not found"], 404);
        }

        $user_id = $user->id;
        
        $userFollowing= Following::where("follower_id", "=", $user_id)->get();

        return response()->json(
            [
            "success" => true,
            "followers" => $userFollowing
            ]
        );
    }

    /**
     * Check if current user is following a user
     *
     * @param Request $request - request object
     * @param Request $userId  - user Id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function currentUserIsFollowingUser(Request $request, $userId)
    {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(["message" => "User not found"], 404);
        }

        $user_id = $user->id;
        if ($user_id == $userId) {
            return response()->json(
                [
                "is_following" => false,
                "message" => "You can not follow yourself"
                ]
            );
        }

        $isFollowing= Following::where(
            [
            ["user_id", "=", $userId],
            ["follower_id", "=", $user_id]
            ]
        )->first();
        
        if ($isFollowing == null) {
            return response()->json(
                [
                "is_following" => false,
                "message" => "You do not follow userId"
                ]
            );
        }

        return response()->json(
            [
            "is_following" => true,
            "followers" => $isFollowing
            ]
        );
    }
}

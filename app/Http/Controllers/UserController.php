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
use DB, Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['only' => 
        [
            'getUser',
            'followUser',
            'getAllUserFollowers'
        ]]);
    }
    
     /**
     * API Register
     *
     * @param Request $request
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

        if($validator->fails()) {
            $error = $validator->messages()->toJson();
            return response()->json(['success'=> false, 'error'=> $error]);
        }

        $username = $request->username;
        $email = $request->email;
        $password = $request->password;
        $user = User::create(['username' => $username, 'email' => $email, 'password' => Hash::make($password)]);

        return response()->json(['success'=> true, 'message'=> 'Thanks for signing up!']);
    }

    /**
     * API Login, on success return JWT Auth token
     *
     * @param Request $request
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

        if($validator->fails()) {
            $error = $validator->messages()->toJson();
            return response()->json(['success'=> false, 'error'=> $error]);
        }

        $credentials = $request->only('email', 'password');
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(
                    ['success' => false,
                    'error' => 'Invalid Credentials. Please make sure you entered a valid email address.'],
                    401
                );
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['success' => false, 'error' => 'could_not_create_token'], 500);
        }
        // all good so return the token
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
     * @param Request $request
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

     public function followUser(Request $request, $userId)
     {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(["message" => "User not found"], 404);
        }

        $user_id = $user->id;

        if ($user_id == $userId) {
            return response()->json([
                "success" => false,
                "message" => "You can not follow yourself"
            ]);
        }
        
        $user= User::where("id", "=", $userId)->first();

        $follow = new Following();

        $followed = $follow->where([
            ['user_id', '=', $userId],
            ['follower_id', '=', $user_id],
        ])->first();

        if (is_null($followed) == false) {
            $follow->where([
                ['user_id', '=', $userId],
                ['follower_id', '=', $user_id],
            ])->delete();
            return response()->json([
                'success' => false,
                'current user unfollowed target user'], 200);
        }

        $following = Following::create([
          "user_id" => $userId,
          "follower_id" => $user_id
        ]);
        
        return response()->json([
            "success" => true,
            "Current user has successfully followed user"]);
     }

    /**
     * Get all of a user's followers
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function getAllUserFollowers(Request $request) {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(["message" => "User not found"], 404);
        }

        $user_id = $user->id;
        
        $userFollowers= Following::where("user_id", "=", $user_id)->get();

        return response()->json([
            "success" => true,
            "followers" => $userFollowers
        ]);
    }

    /**
     * Get all users current user is following
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function getAllUserFollowing(Request $request) {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(["message" => "User not found"], 404);
        }

        $user_id = $user->id;
        
        $userFollowing= Following::where("follower_id", "=", $user_id)->get();

        return response()->json([
            "success" => true,
            "followers" => $userFollowing
        ]);
    }

    /**
     * Check if current user is following a user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function currentUserIsFollowingUser(Request $request, $userId) {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(["message" => "User not found"], 404);
        }

        $user_id = $user->id;
        if ($user_id == $userId) {
            return response()->json([
                "is_following" => false,
                "message" => "You can not follow yourself"
            ]);
        }

        $isFollowing= Following::where([
            ["user_id", "=", $userId],
            ["follower_id", "=", $user_id]
        ])->first();
        
        if ($isFollowing == null) {
            return response()->json([
                "is_following" => false,
                "message" => "You do not follow userId"
            ]);
        }

        return response()->json([
            "is_following" => true,
            "followers" => $isFollowing
        ]);
    }

}

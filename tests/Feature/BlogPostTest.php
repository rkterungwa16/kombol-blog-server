<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;

use JWTAuth;
use Carbon\Carbon;
use JWTFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

use Illuminate\Foundation\Testing\RefreshDatabase;

class BlogPostTest extends TestCase
{
    use WithoutMiddleware;
    /**
     * Getting authenticated users blog posts.
     *
     * @return void
     */
    public function testsGettingAuthenticatedUsersBlogPostsSuccessfully()
    {
        $user = factory(User::class)->create([
            'email' => 'testlogin@user.com',
            'password' => 'john123',
        ]);
       
        $token = JWTAuth::fromUser($user);
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->get('/api/v1/blog/posts', $headers);

        $response->assertStatus(200);
    }

    public function testsRegistersSuccessfully()
    {
        $payload = [
            'username' => 'John',
            'email' => 'john@gmail.com',
            'password' => 'john123',
            'password_confirmation' => 'john123',
        ];

        $response = $this->post('/api/v1/register', $payload);
        $response->assertStatus(200);      
    }

    public function testsLoginSuccessfully()
    {
        $user = factory(User::class)->create([
            'email' => 'rich@gmail.com',
            'password' => '654321'
        ]);
        // dd(User::find(1));
        $payload = [
            'email' => 'rich@gmail.com',
            'password' => '654321'
        ];

        $response = $this->json('POST', '/api/v1/login', $payload);

        $response->assertStatus(200);   
    }
    
    /**
     * Create blog posts for authenticated user authenticated users blog posts.
     *
     * @return void
     */
    public function testsCreatingAuthenticatedUsersBlogPostsSuccessfully()
    {
        $user = factory(User::class)->create([
            'email' => 'testlogin@user.com',
            'password' => 'john123',
        ]);
       
        $token = JWTAuth::fromUser($user);

        $post = [
            "title" => "title",
            "content" => "content for the tests"
        ];

        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->post('/api/v1/blog/post', $post, $headers);
        $response->assertStatus(200);
    }

    /**
     * Edit blog posts for authenticated user authenticated users blog posts.
     *
     * @return void
     */
    public function testsEditingAuthenticatedUsersBlogPostsSuccessfully()
    {
        $user = factory(User::class)->create([
            'email' => 'testlogin@user.com',
            'password' => 'john123',
        ]);
       
        $token = JWTAuth::fromUser($user);

        $post = [
            "title" => "title",
            "content" => "content for the tests"
        ];

        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->patch('/api/v1/blog/post/1', $post, $headers);
        $response->assertStatus(200);
    }

    /**
     * Delete blog posts for authenticated user authenticated users blog posts.
     *
     * @return void
     */
    public function testsDeletingAuthenticatedUsersBlogPostsSuccessfully()
    {
        $user = factory(User::class)->create([
            'email' => 'testlogin@user.com',
            'password' => 'john123',
        ]);
       
        $token = JWTAuth::fromUser($user);

        $post = [
            "title" => "title",
            "content" => "content for the tests"
        ];

        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->delete('/api/v1/blog/post/1', $post, $headers);
        $response->assertStatus(200);
    }

    /**
     * Like blog posts for authenticated user authenticated users blog posts.
     *
     * @return void
     */
    public function testsGetLikesForBlogPostSuccessfully()
    {
        
        $user = factory(User::class)->create([
            'email' => 'testlogin@user.com',
            'password' => 'john123',
        ]);
       
        $token = JWTAuth::fromUser($user);

        $headers = ['Authorization' => "Bearer $token"];
        $response = $this->get("/api/v1/post/likes/1", $headers);
        $response->assertStatus(200);
    }

    /**
     * Like blog posts for authenticated user authenticated users blog posts.
     *
     * @return void
     */
    public function testsLikeBlogPostSuccessfully()
    {
        
        $user = factory(User::class)->create([
            'email' => 'testlogin@user.com',
            'password' => 'john123',
        ]);
       
        $token = JWTAuth::fromUser($user);

        $headers = ['Authorization' => "Bearer $token"];
        $response = $this->post("/api/v1/post/like/1", [], $headers);
        $response->assertStatus(200);
    }

     /**
     * Create comments for authenticated user authenticated users blog posts.
     *
     * @return void
     */
    public function testsCreatingAuthenticatedUsersCommentsSuccessfully()
    {
        $user = factory(User::class)->create([
            'email' => 'testlogin@user.com',
            'password' => 'john123',
        ]);
       
        $token = JWTAuth::fromUser($user);

        $comment = [
            "comment" => "title",
        ];

        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->post('/api/v1/post/comment/1', $comment, $headers);
        $response->assertStatus(200);
    }

    /**
     * Get comments for authenticated user authenticated users blog posts.
     *
     * @return void
     */
    public function testsGettingAuthenticatedUsersCommentsSuccessfully()
    {
        $user = factory(User::class)->create([
            'email' => 'testlogin@user.com',
            'password' => 'john123',
        ]);
       
        $token = JWTAuth::fromUser($user);

        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->get('/api/v1/post/comments/1', $headers);
        $response->assertStatus(200);
    }
}

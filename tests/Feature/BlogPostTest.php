<?php

namespace Tests\Feature;

use App\Models\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

use Illuminate\Foundation\Testing\RefreshDatabase;

class BlogPostTest extends TestCase
{
    // use WithoutMiddleware;
    // use CreatesApplication, DatabaseMigrations;

    // public function setUp()
    // {
    //     parent::setUp();
    //     factory('App\Models\User')->create();
    // }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        
        $user = factory(User::class)->create([
            "username" => "terungwa",
            "email" => "ter@gmail.com",
            "password" => "123456"
        ]);
        $token = $user->generateToken();
        // dd($token);
        $headers = ['Authorization' => "Bearer $token"];
        $response = $this->get('/api/v1/blog/posts', $headers);
       
        $response->assertStatus(200);
    }

    // public function testUserLoginsSuccessfully()
    // {
    //     $user = factory(User::class)->create([
    //         'email' => 'testlogin@user.com',
    //         'password' => bcrypt('toptal123'),
    //         'username' => 'username'
    //     ]);

    //     $payload = ['email' => 'testlogin@user.com', 'password' => 'toptal123'];

    //     $this->json('POST', 'api/v1/login', $payload)
    //         ->assertStatus(200)
    //         ->assertJsonStructure([
    //             'data' => [
    //                 'id',
    //                 'name',
    //                 'email',
    //                 'created_at',
    //                 'updated_at',
    //                 'api_token',
    //             ],
    //         ]);

    // }
}

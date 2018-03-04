<?php

namespace Tests\Feature;

use App\Models\User;

use JWTAuth;
use Carbon\Carbon;
use JWTFactory;
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
        
        // $user = factory(User::class)->create([
        //     "username" => "best",
        //     "email" => "best@gmail.com",
        //     "password" => "123456"
        // ]);


        $exp = Carbon::now()->timestamp + 5 * 60 * 60;
        $factory = JWTFactory::customClaims([
            'sub'   => env('API_ID'),
            'iss'   => config('app.name'),
            'iat'   => Carbon::now()->timestamp,
            'exp'   => $exp,
            'nbf'   => Carbon::now()->timestamp,
            'jti'   => uniqid(),
            'email' => 'testlogin@user.com',
            'password' => 'john123'
        ]);

        $payload = $factory->make();
        $token = JWTAuth::encode($payload);
        
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
        // dd($response);
        $response->assertStatus(200);      
    }

    public function testsLoginSuccessfully()
    {
        $user = factory(User::class)->create([
            'email' => 'testlogin@user.com',
            'password' => 'john123',
        ]);

        $payload = [
            'email' => 'testlogin@user.com',
            'password' => 'john123',
        ];

        $response = $this->post('/api/v1/login', $payload);
        $response->assertStatus(200); 
            
    }

    
}

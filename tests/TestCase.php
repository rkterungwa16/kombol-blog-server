<?php

namespace Tests;
use App\Models\User;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        Artisan::call('db:seed');

        $user = factory(User::class)->create([
            'email' => 'testlogin@user.com',
            'password' => 'john123',
        ]);

        // $user = factory(User::class)->create([
        //     "username" => "best",
        //     "email" => "best@gmail.com",
        //     "password" => "123456"
        // ]);
    }
    
}

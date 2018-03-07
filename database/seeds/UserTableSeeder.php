<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new App\Models\User([
            'username' => 'Richard',
            'email' => 'richard@gmail.com',
            'password' => '123456'
        ]);
        $user->save();
    }
}

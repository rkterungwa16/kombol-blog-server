<?php

use Illuminate\Database\Seeder;

class FollowingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $following = new App\Models\Following([
            'user_id' => 1,
            'follower_id' => 2,
            'user_email' => 'richard@gmail.com',
            'user_username' => 'richard',
            'follower_email' => 'testlogin@user.com',
            'follower_username' => 'john'
        ]);
        $following->save();
    }
}

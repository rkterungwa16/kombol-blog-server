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
        ]);
        $following->save();
    }
}

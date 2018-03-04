<?php

use Illuminate\Database\Seeder;

class LikeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $like = new App\Models\Like([
            'user_id' => 1,
            'post_id' => 1,
        ]);
        $like->save();
    }
}

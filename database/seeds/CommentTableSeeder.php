<?php

use Illuminate\Database\Seeder;

class CommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comment = new App\Models\Comment([
            'post_id' => 1,
            'user_id' => 1,
            'comment' => 'I just commented here',
        ]);
        $comment->save();
    }
}

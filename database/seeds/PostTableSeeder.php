<?php

use Illuminate\Database\Seeder;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $post = new App\Models\Post([
            'title' => 'Title to write',
            'content' => 'What to say now friend',
            'user_id' => '1',
            'author' => 'richard'
        ]);
        $post->save();
    }
}

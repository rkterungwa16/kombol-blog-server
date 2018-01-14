<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Session\Store;

class BlogController extends Controller
{
  public function getIndex(Store $session)
  {
    $post = new Post();
    $posts = $post->getPosts($session);
    return view('blog.index', ['posts' => $posts]);
  }

  public function getPost(Store $session, $id)
  {
    $post = new Post();
    $post = $post->getPost($session, $id);
    return view('blog.post', ['post' => $post]);
  }

  public function getPosts(Store $session, Request $request)
  {
    $post = new Post();
    return $post;
  }

  public function postAdminCreate(Store $session, Request $request)
  {
    $this->validate($request, [
        'title' => 'required|min:5',
        'content' => 'required|min:10'
    ]);

    $post = new Post([
      'title' => $request->input('title'),
      'content' => $request->input('content')
    ]);

    $post->save();
    return redirect()->route('blog.index');
  }
}

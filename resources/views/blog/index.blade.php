@extends('layouts.master')

@section('content')
  <div>
    <div>
    <h1 class="post-info bold">Your blogs</h1>
    </div>
    <hr>
    <div class="your-blogs">
      @foreach($posts as $post)
      <div class="card">
        <p class="quote">{{ $post['title'] }}</p>
        <div class="container">
          <h1 class="post-info">{{ $post['title'] }}</h1>
          <p class="post-info">Ok now what</p>
          <p class="post-info"><a href="#">Read more...</a></p>
        </div>
      </div>
      @endforeach
    </div>

    <h1 class="post-info bold">Your reading list</h1>
    </div>
    <hr>
    <div class="card">
      <p class="quote">The beautiful Laravel</p>
      <div class="container">
        <h1 class="post-info">This is the blog</h1>
        <p class="post-info">Ok now what</p>
        <p class="post-info"><a href="#">Read more...</a></p>
      </div>
    </div>

    <div>
    <h1 class="post-info bold">Collections</h1>
    </div>
    <hr>
    <div class="card">
      <p class="quote">The beautiful Laravel</p>
      <div class="container">
        <h1 class="post-info">This is the blog</h1>
        <p class="post-info">Ok now what</p>
        <p class="post-info"><a href="#">Read more...</a></p>
      </div>
    </div>

    <div>
    <h1 class="post-info bold">Popular</h1>
    </div>
    <hr>
    <div class="card">
      <p class="quote">The beautiful Laravel</p>
      <div class="container">
        <h1 class="post-info">This is the blog</h1>
        <p class="post-info">Ok now what</p>
        <p class="post-info"><a href="#">Read more...</a></p>
      </div>
    </div>
  <div>
@endsection
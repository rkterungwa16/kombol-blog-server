@extends('layouts.master')

@section('content')
  <div>
    <div class="post_container">
      <form action="{{ route('admin.create') }}" method="post">
        <label for="fname">Post title</label>
        <input type="text" id="title" name="title" placeholder="Your name..">

        <label for="subject">Post content</label>
        <textarea id="content" name="content" placeholder="Write something.." style="height:200px"></textarea>
        {{ csrf_field() }}
        <input type="submit" value="Submit">
      </form>
    </div>
  </div>
@endsection

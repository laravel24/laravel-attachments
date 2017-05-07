<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Post;

class PostsController extends Controller
{

  public function create()
  {
    $post = Post::draft();
    return $this->edit($post);
  }

  public function index()
  {
    return Post::notDraft()->get();
  }

  public function edit(Post $post)
  {
    return view('posts.edit', [
      'post' => $post,
      'editor' => 'tinymce'
    ]);
  }

  public function update(Request $request, Post $post)
  {
    $post->update($request->all());
    return redirect()->route('posts.edit', [ 'id' => $post->id ])->with('success', 'Article was updated');
  }

}

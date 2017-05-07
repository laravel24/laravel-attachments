@extends('application')

@section('content')
  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  <h2>Edit post {{ $post->name }}</h2>

  <form action="{{ route('posts.update', [ 'id' => $post->id ]) }}" method="post">
    {{ csrf_field() }}
    {{ method_field('PATCH') }}

    <div class="form-group">
      <input type="text" class="form-control" name="name" value="{{ $post->name }}">
    </div>

    <div class="form-group">
      <textarea data-id="{{ $post->id }}" data-type="{{ get_class($post) }}"
        data-url="{{ route('attachments.store') }}" name="content" id="editor" class="form-control">{{ $post->content }}</textarea>
    </div>

    <button class="btn btn-primary">Update</button>
  </form>
@endsection

@if($editor == 'tinymce')
  @section('js')
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="/js/editor.js"></script>
  @endsection
@endif

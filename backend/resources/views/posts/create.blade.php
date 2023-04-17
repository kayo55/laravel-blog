@extends('layouts.app')

@section('title', 'Create Post')

@section('content')
<form action="{{route('post.store')}}" method="post" enctype="multipart/form-data">
  @csrf
  <div class="mb-3">
      <label for="title" class="form-label text-muted">Title</label>
      <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control" placeholder="Enter title here">
      {{-- Error --}}
      @error('title')
          <p class="text-danger small">{{ $message }}</p>
      @enderror
  </div>
  <div class="mb-3">
      <label for="body" class="form-label text-muted">Body</label>
      <textarea name="body" id="body" rows="5" class="form-control" placeholder="Start writing...">{{ old('body') }}</textarea>
      {{-- Error --}}
      @error('body')
          <p class="text-danger small">{{ $message }}</p>
      @enderror
  </div>
  <div class="mb-3">
      <label for="image" class="form-label text-muted">Image</label>
      <input type="file" name="image" id="image" class="form-control" aria-describedby="image-info">
      <div class="form-text" id="image-info">
          Acceptable formats: jpeg, jpg, png, gif only<br>
          Maximum file size: 1048kb
      </div>
      {{-- Error --}}
      @error('image')
          <p class="text-danger small">{{ $message }}</p>
      @enderror
  </div>
  <button type="submit" class="btn btn-primary px-5">Post</button>
</form>
@endsection
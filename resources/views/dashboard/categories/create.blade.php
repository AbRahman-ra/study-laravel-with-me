@extends('layouts.dashboard')
@section('title', 'Categories')

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->

  @section('breadcrumb')
  @parent
  <li class="breadcrumb-item">Categories</li>
  <li class="breadcrumb-item active">Create</li>
  @endsection

  <form action="{{ route('categories.store') }}" method="post">
    @csrf
    @method('POST')
    <div class="form-group">
      <label for="name">Name</label>
      <input type="text" name="name" required id="name" class="form-control">

      <select name="parent_id" id="parent_id" class="form-select form-control mb-5">
        <option value="" selected disabled>Select a parent category</option>
        @forelse($categories as $category)
        <option value="{{ $category->id }}">{{ $category->name }}</option>
        @empty
        @endforelse
      </select>

      <input type="file" name="image_url" id="image_url" accept="image/jpg, image/png, image/svg">
      <input class="btn btn-success" type="submit" value="Create">
    </div>
  </form>
  @endsection
</div>
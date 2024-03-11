@extends('layouts.dashboard')
@section('title', 'Categories')

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->

  @section('breadcrumb')
  @parent
  <li class="breadcrumb-item active">Categories</li>
  @endsection

  <div class="btn btn-success mb-5">
    <a href="{{route('categories.create')}}">Create Category</a>
  </div>

  @if(session()->has('successMessage'))
  <div class="alert alert-success">
    {{ session()->get('successMessage') }}
  </div>
  @endif

  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Parent</th>
        <th>Created At</th>
        <th colspan="2">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($categories as $category)
      <tr>
        <td> {{ $category->id }} </td>
        <td> {{ $category->name }} </td>
        <td> {{ $category->parent_id ?? "-" }} </td>
        <td> {{ $category->created_at }} </td>
        <td><a href="{{ route('categories.edit', $category->id) }}" class="btn btn-info">Edit</a></td>
        <td>
          <form action="{{ route('categories.destroy', $category->id) }}">
            @csrf
            @method('DELETE')
            <input class="btn btn-danger" type="submit" value="Delete">
          </form>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="6">No categories found</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
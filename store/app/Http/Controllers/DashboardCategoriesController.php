<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardCategoriesController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $categories = Category::all();
    return view('dashboard.categories.index', ['categories' => $categories]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $categories = Category::all(['name', 'id']);
    return view('dashboard.categories.create', ['categories' => $categories]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->merge(['slug' => Str::slug($request->post('name'))]);
    $category = Category::create($request->all());
    return redirect()->route('categories.index')->with('successMessage', 'Category has been added successfully');
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }
}

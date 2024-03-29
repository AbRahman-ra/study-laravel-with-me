<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
  public function index()
  {
    return Response::view('dashboard.index', ['user' => auth()->user()]);
  }
}

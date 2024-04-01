<?php

use App\Http\Controllers\DashboardCategoriesController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])
  ->middleware(['auth'])
  ->name('dashboard');

Route::resource('dashboard/categories', DashboardCategoriesController::class);

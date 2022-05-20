<?php

use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');
Route::get("/courses", [TableController::class, "courses"]);
Route::get("/classes", [TableController::class, "classes"]);
Route::get("/lectures", [TableController::class, "lectures"]);

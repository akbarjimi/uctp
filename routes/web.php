<?php

use App\Http\Controllers\LectureController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get("/table", [TableController::class, "table"]);
Route::get("/courses", [TableController::class, "courses"]);
Route::get("/lectures", [TableController::class, "lectures"]);

Route::get("/rooms/{name}", [RoomController::class, "show"]);
Route::get("/lectures/{lecture}", [LectureController::class, "show"]);

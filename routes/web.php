<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get("/courses/{id}/{title}", [CourseController::class, "show"]);
Route::get("/rooms/{name}", [RoomController::class, "show"]);
Route::get("/lectures/{lecture}", [LectureController::class, "show"]);

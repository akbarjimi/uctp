<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name("home");

Route::prefix('courses')->name('course.')->group(function () {
    Route::get("/", [CourseController::class, "index"])->name('index');
    Route::get("{id}/{title}", [CourseController::class, "show"])->name('show');
});

Route::prefix('rooms')->name('room.')->group(function () {
    Route::get("/", [RoomController::class, "index"])->name('index');
    Route::get("{room}", [RoomController::class, "show"])->name('show');
});

Route::prefix('lectures')->name('lecture.')->group(function () {
    Route::get("/", [LectureController::class, "index"])->name('index');
    Route::get("{lecture}", [LectureController::class, "show"])->name('show');
});


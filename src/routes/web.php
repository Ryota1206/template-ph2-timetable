<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\TimetableEntryController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('guest')->group(function () {
    Route::view('/register', 'auth.register')->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/timetable', [TimetableController::class, 'index']);
    Route::get('/entries', [TimetableEntryController::class, 'index']);
    Route::get('/entries/create', [TimetableEntryController::class, 'create']);
    Route::post('/entries', [TimetableEntryController::class, 'store']);
    Route::delete('/entries/{id}', [TimetableEntryController::class, 'destroy']);
    Route::get('/courses', [CourseController::class, 'index']);
});
Route::post('/reviews',[ReviewController::class,'store'])
->middleware('auth');
Route::get('/reviews/create',[ReviewController::class,'create'])
->middleware('auth');
Route::get('/courses/{course}',[CourseController::class,'show'])
->middleware('auth');
Route::delete('/reviews/{review}',[ReviewController::class,'destroy'])
->middleware('auth');
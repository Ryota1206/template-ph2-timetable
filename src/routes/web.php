<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\TimetableEntryController;
use App\Http\Controllers\CourseController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/timetable', [TimetableController::class,'index']);
Route::get('/entries',[TimetableEntryController::class,'index']);
Route::get('/entries/create',[TimetableEntryController::class,'create']);
Route::post('/entries',[TimetableEntryController::class,'store']);
Route::delete('/entries/{id}',[TimetableEntryController::class,'destroy']);
Route::get('/courses',[CourseController::class,'index']);
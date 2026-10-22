<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\TimetableEntryController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/timetable', [TimetableController::class,'index']);
Route::get('/entries',[TimetableEntryController::class,'index']);
Route::get('/entries/create',[TimetableEntryController::class,'create']);
Route::post('/entries',[TimetableEntryController::class,'store']);
Route::get('/entries/{id}/edit',[TimetableEntryController::class,'edit']);
Route::put('/entries/{id}',[TimetableEntryController::class,'update']);
Route::delete('/entries/{id}',[TimetableEntryController::class,'destroy']);
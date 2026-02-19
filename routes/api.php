<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/v1/users', [UserController::class, 'index']);
Route::post('/v1/users', [UserController::class, 'store']);
Route::get('/v1/users/{id}', [UserController::class, 'show']);
Route::put('/v1/users/{id}', [UserController::class, 'update']);
Route::patch('/v1/users/{id}', [UserController::class, 'update']);
Route::delete('/v1/users/{id}', [UserController::class, 'destroy']);
Route::post('/v1/users/{id}/restore', [UserController::class, 'restore']);

use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\LoanController;

Route::get('/books', [BookController::class, 'index']);
Route::post('/loans', [LoanController::class, 'store']);
Route::post('/returns/{id}', [LoanController::class, 'returnBook']);
Route::get('/loans/history', [LoanController::class, 'history']);

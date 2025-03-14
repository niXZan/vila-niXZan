<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/posts', [\App\Http\Controllers\PostController::class, 'index']);

Route::get('/post', [\App\Http\Controllers\PostController::class, 'index']);

Route::get('/post/{id}', [\App\Http\Controllers\PostController::class, 'show']);
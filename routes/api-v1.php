<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\UserController;

Route::post('login', [LoginController::class, 'store']);

Route::post('register', [RegisterController::class, 'store'])->name('api.v1.register');

Route::apiResource('categories', CategoryController::class)->names('api.v1.categories'); // Endpoints categorias

Route::apiResource('posts', PostController::class)->names('api.v1.posts'); // Endpoints posts

Route::apiResource('tags', TagController::class)->names('api.v1.tags'); // Endpoints tags

Route::resource('users', UserController::class)->only(['update'])->names('api.v1.users');

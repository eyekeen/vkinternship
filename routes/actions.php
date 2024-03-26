<?php

use App\Application\Router\Route;
use App\Controllers\UserController;
use App\Middleware\AuthMiddleware;



Route::post('/register', UserController::class, 'register');
Route::post('/authorize', UserController::class, 'login');
Route::post('/logout', UserController::class, 'logout');
Route::page('/feed', UserController::class, 'feed', AuthMiddleware::class);

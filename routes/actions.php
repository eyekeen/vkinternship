<?php

use App\Application\Router\Route;
use App\Controllers\ContactsControlle;
use App\Controllers\UserController;



Route::post('/register', UserController::class, 'register');
Route::post('/authorize', UserController::class, 'login');
Route::post('/logout', UserController::class, 'logout');

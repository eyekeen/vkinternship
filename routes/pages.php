<?php

use App\Application\Router\Route;
use App\Controllers\PagesController;
use App\Middleware\GuestMiddleware;
use App\Middleware\AuthMiddleware;

Route::page('/home', PagesController::class, 'home');
Route::page('/about', PagesController::class, 'about');
Route::page('/feed', PagesController::class, 'contacts', AuthMiddleware::class);
Route::page('/login', PagesController::class, 'login', GuestMiddleware::class);
Route::page('/register', PagesController::class, 'register', GuestMiddleware::class);

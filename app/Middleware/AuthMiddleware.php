<?php

namespace App\Middleware;

use App\Application\Middleware\Middleware;
use App\Application\Auth\Auth;


class AuthMiddleware extends Middleware {
    
    public function handle() {
        if(!Auth::check()){
            echo 'unauthorized';
        }
    }
}

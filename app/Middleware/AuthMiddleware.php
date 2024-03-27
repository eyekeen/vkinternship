<?php

namespace App\Middleware;

use App\Application\Middleware\Middleware;

use App\Application\Helpers\TokenHelper;



class AuthMiddleware extends Middleware
{

    public function handle()
    {
        $auth_token = getallheaders()['Authorization'] ?? false;

        TokenHelper::tokenValid($auth_token);
    }
}

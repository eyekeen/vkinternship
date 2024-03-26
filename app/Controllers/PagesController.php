<?php

namespace App\Controllers;

use App\Application\Views\View;
use App\Application\Response\Response;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class PagesController
{
    public function feed(): void {
        http_response_code(200);
        header('content-type: application/json');
    }

}

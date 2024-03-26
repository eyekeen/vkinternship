<?php

namespace App\Middleware;

use App\Application\Middleware\Middleware;
use App\Application\Config\Config;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;


class AuthMiddleware extends Middleware {
    
    public function handle() {
        
        if(!$auth_token = explode(' ', getallheaders()['Authorization'])[1]){
            $response = json_encode(['error' => 'unauthorized']);
            http_response_code(403);
            header('content-type: application/json');
            echo $response;
        }

        $secret_key = Config::get('auth.jwt_secret');

        try {
            $decoded = JWT::decode($auth_token, new Key($secret_key, 'HS256'));
        } catch (SignatureInvalidException $th) {
            $response = json_encode(['error' => 'unauthorized']);
            http_response_code(403);
            header('content-type: application/json');
            echo $response;
        }
    }
}

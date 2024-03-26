<?php

namespace App\Middleware;

use App\Application\Middleware\Middleware;
use App\Application\Config\Config;
use App\Application\Response\Response;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;


class AuthMiddleware extends Middleware {
    
    public function handle() {
        $auth_token = getallheaders()['Authorization'] ?? false;
        
        if(!$auth_token){
            $response = json_encode(['error' => 'unauthorized']);
            http_response_code(403);
            header('content-type: application/json');
            echo $response;
            exit;
        }
        
        $secret_key = Config::get('auth.jwt_secret');
        
        $auth_token = explode(' ', $auth_token)[1];

        try {
            $decoded = JWT::decode($auth_token, new Key($secret_key, 'HS256'));
        } catch (SignatureInvalidException $th) {
            $response = json_encode(['error' => 'unauthorized']);
            http_response_code(403);
            header('content-type: application/json');
            echo $response;
            exit;
        }
    }
}

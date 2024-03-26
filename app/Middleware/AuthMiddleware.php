<?php

namespace App\Middleware;

use App\Application\Middleware\Middleware;
use App\Application\Config\Config;
use App\Models\User;


use Symfony\Component\HttpFoundation\Response;


use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;


class AuthMiddleware extends Middleware
{

    public function handle()
    {
        $auth_token = getallheaders()['Authorization'] ?? false;

        if (!$auth_token) {
            $response = new Response(
                json_encode(['error' => 'unauthorized']),
                Response::HTTP_UNAUTHORIZED,
                ['content-type' => 'application/json']
            );
            $response->send();
            exit;
        }


        $secret_key = Config::get('auth.jwt_secret');

        $auth_token = explode(' ', $auth_token)[1];

        try {
            $decoded = (array) JWT::decode($auth_token, new Key($secret_key, 'HS256'));

            if (!$user = (new User())->find('token', $secret_key)) {
                $response = new Response(
                    json_encode(['error' => 'unauthorized']),
                    Response::HTTP_UNAUTHORIZED,
                    ['content-type' => 'application/json']
                );
                $response->send();
                exit;
            }

        } catch (SignatureInvalidException $th) {
            $response = new Response(
                json_encode(['error' => 'unauthorized']),
                Response::HTTP_UNAUTHORIZED,
                ['content-type' => 'application/json']
            );
            $response->send();
            exit;
        }
    }
}

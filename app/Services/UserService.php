<?php

namespace App\Services;

use App\Application\Request\Request;
use App\Application\Response\Response;
use App\Models\User;
use App\Application\Auth\Auth;

use App\Application\Config\Config;

use Firebase\JWT\JWT;

/**
 * Description of UserService
 *
 * @author tarum2
 */
class UserService
{
    public function register(Request $request): string|bool
    {
        $user = new User();

        $user->setEmail($request->post('email'));
        $user->setPassword($request->post('password'));


        $user->store();

        $user_id = $user->find('email', $user->getEmail())->getId();

        http_response_code(200);
        return json_encode([
            'user_id' => $user_id,
            'password_check_status' => 'good'
        ]);
    }

    public function login(Request $request)
    {
        $user = (new User())->find('email', $request->post('email'));

        if ($user) {
            if (password_verify($request->post('password'), $user->getPassword())) {
                
                $secret_key = Config::get('auth.jwt_secret');
                $payload = [
                    'user_id' => $user->getId(),
                ];

                $jwt = JWT::encode($payload, $secret_key, 'HS256');

                $user->update(['token' => $secret_key]);

                Response::json(201, ['access_token' => $jwt]);
            } else {
                Response::json(403, ['error' => 'wrong email or password']);
                exit;
            }
        } else {
            Response::json(404, ['msg' => 'User not found']);
            exit;
        }
    }
}

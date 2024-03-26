<?php

namespace App\Services;

use App\Application\Request\Request;
use App\Models\User;
use App\Application\Helpers\Random;
use App\Application\Auth\Auth;

use App\Application\Config\Config;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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
                    $user->getId(),
                ];

                $jwt = JWT::encode($payload, $secret_key, 'HS256');
                // $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));

                $user->update(['token' => $secret_key]);
                setcookie(Auth::getTokenColumn(), $jwt);
                echo json_encode(['access_toekn' => $jwt]);
            } else {
                // TODO: add error message
                echo 'wrong email or password';
            }
        } else {
            dd('User not found');
        }
    }
}

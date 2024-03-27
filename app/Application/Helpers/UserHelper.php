<?php

namespace App\Application\Helpers;

use Symfony\Component\HttpFoundation\Request;

use App\Application\Helpers\ResponseHelper;
use App\Models\User;

use App\Application\Config\Config;

use Firebase\JWT\JWT;

class UserHelper
{
    public static function checkData(User $user, string $email, string $password): void
    {
        // Validate password strength
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            ResponseHelper::badRequst(['error' => 'invalid email']);
        }

        if ($user->find('email', $email)) {
            ResponseHelper::conflict(['error' => 'this email already taken']);
        }

        if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
            ResponseHelper::badRequst(['error' => 'weak_password', 'notice' => 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character']);
        }
    }

    public static function checkLogin(Request $request)
    {
        $user = (new User())->find('email', $request->request->get('email'));

        if ($user) {
            if (password_verify($request->request->get('password'), $user->getPassword())) {

                $expired_at = new \DateTime('now');
                $expired_at->modify('+1 day');
                $expired_at = $expired_at->format('Y-m-d H:i:s');


                $secret_key = Config::get('auth.jwt_secret');
                $payload = [
                    'user_id' => $user->getId(),
                    'sub' => 'feed access token',
                    'exp' => $expired_at,
                ];

                $jwt = JWT::encode($payload, $secret_key, 'HS256');

                $user->update(['token' => $jwt]);
                $user->update(['expired_at' => $expired_at]);

                ResponseHelper::created(['access_token' => $jwt]);
            } else {
                ResponseHelper::badRequst(['error' => 'wrong email or password']);
            }
        } else {
            ResponseHelper::badRequst(['error' => 'wrong email or password']);
        }
    }
}
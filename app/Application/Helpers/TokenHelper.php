<?php

namespace App\Application\Helpers;

use App\Models\User;

use App\Application\Config\Config;


use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;

class TokenHelper
{
    public static function tokenValid(string|bool $auth_token) {

        if (!$auth_token) {
            ResponseHelper::unauthorized(['error' => 'unauthorized']);
        }


        $secret_key = Config::get('auth.jwt_secret');

        $auth_token = explode(' ', $auth_token)[1];

        try {
            $decoded = (array) JWT::decode($auth_token, new Key($secret_key, 'HS256'));

            $user = (new User())->find('id', $decoded['user_id']);
            
            $exp_date = new \DateTime($user->getExpiredAt()) < new \DateTime('now');
            
            if (
                !$user->getToken() ||
                ($user->getToken() && $exp_date)
                || $auth_token != $user->getToken()
            ) {
                ResponseHelper::unauthorized(['error' => 'unauthorized']);
            }

            return $user;

        } catch (SignatureInvalidException $th) {
            ResponseHelper::unauthorized(['error' => 'unauthorized']);
        }
    }
}
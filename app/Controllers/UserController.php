<?php

namespace App\Controllers;

use App\Models\User;
use App\Application\Request\Request;
use App\Application\Config\Config;
use App\Application\Auth\Auth;
use App\Services\UserService;

use App\Application\Response\Response;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use Firebase\JWT\SignatureInvalidException;

class UserController
{

    private UserService $service;

    public function __construct()
    {
        $this->service = new UserService();
    }

    public function register(Request $request): void
    {
        // TODO: make validation data

        $user = new User();
        $email = $request->post('email');
        $password = $request->post('password');

        // Validate password strength
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('content-type: application/json');
            Response::json(412, ['error' => 'invalid email']);
            exit;
        }

        if ($user->find('email', $email)) {
            header('content-type: application/json');
            Response::json(409, ['error' => 'this email already taken']);
            exit;
        }

        if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
            header('content-type: application/json');
            Response::json(412, ['error' => 'weak_password', 'notice' => 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character']);
            exit;
        }

        echo $this->service->register($request);
    }

    public function login(Request $request)
    {
        header('content-type: application/json');
        $this->service->login($request);
    }

    public function logout()
    {
        unset($_COOKIE[Auth::getTokenColumn()]);
        setcookie(Auth::getTokenColumn(), NULL);
        
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
            $decode = (array)JWT::decode($auth_token, new Key($secret_key, 'HS256'));
        
            $user = (new User())->find('id', $decode['user_id']);
            $user->update(['token' => null]);
            $response = json_encode(['msg' => 'user logout']);
            http_response_code(200);
            header('content-type: application/json');
            echo $response;
        } catch (SignatureInvalidException $th) {
            $response = json_encode(['error' => 'unauthorized']);
            http_response_code(403);
            header('content-type: application/json');
            echo $response;
            exit;
        }


        
    }
}

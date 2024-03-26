<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

use App\Application\Config\Config;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use Firebase\JWT\SignatureInvalidException;


class UserService
{
    public function register(Request $request): void
    {
        $user = new User();

        $email = $request->request->get('email');
        $password = $request->request->get('password');

        // validation before register
        $this->check($user, $email, $password);

        $user->setEmail($email);
        $user->setPassword($password);


        $user->store();

        $user_id = $user->find('email', $user->getEmail())->getId();

        $response = new Response(
            json_encode(
                [
                    'user_id' => $user_id,
                    'password_check_status' => 'good'
                ]
            ),
            Response::HTTP_CREATED,
            ['content-type' => 'application/json']
        );
        $response->send();
    }

    // validation for registration
    public function check(User $user, string $email, string $password): void
    {
        // Validate password strength
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response = new Response(
                json_encode(['error' => 'invalid email']),
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/json']
            );
            $response->send();
            exit;
        }

        if ($user->find('email', $email)) {
            $response = new Response(
                json_encode(['error' => 'this email already taken']),
                Response::HTTP_CONFLICT,
                ['content-type' => 'application/json']
            );
            $response->send();
            exit;
        }

        if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
            $response = new Response(
                json_encode(['error' => 'weak_password', 'notice' => 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character']),
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/json']
            );
            $response->send();
            exit;
        }
    }

    public function login(Request $request): void
    {
        $user = (new User())->find('email', $request->request->get('email'));

        if ($user) {
            if (password_verify($request->request->get('password'), $user->getPassword())) {

                $secret_key = Config::get('auth.jwt_secret');
                $payload = [
                    'user_id' => $user->getId(),
                ];

                $jwt = JWT::encode($payload, $secret_key, 'HS256');

                $user->update(['token' => $secret_key]);

                $response = new Response(
                    json_encode(['access_token' => $jwt]),
                    Response::HTTP_CREATED,
                    ['content-type' => 'application/json']
                );
                $response->send();
            } else {
                $response = new Response(
                    json_encode(['error' => 'wrong email or password']),
                    Response::HTTP_BAD_REQUEST,
                    ['content-type' => 'application/json']
                );
                $response->send();
                exit;
            }
        } else {
            $response = new Response(
                json_encode(['msg' => 'User not found']),
                Response::HTTP_NOT_FOUND,
                ['content-type' => 'application/json']
            );
            $response->send();
            exit;
        }
    }

    public function logout(): void
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
            $decode = (array) JWT::decode($auth_token, new Key($secret_key, 'HS256'));

            $user = (new User())->find('id', $decode['user_id']);
            $user->update(['token' => null]);


            $response = new Response(
                json_encode(['msg' => 'user logout']),
                Response::HTTP_OK,
                ['content-type' => 'application/json']
            );
            $response->send();
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

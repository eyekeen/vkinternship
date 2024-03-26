<?php

namespace App\Controllers;

use App\Models\User;
use App\Application\Request\Request;
use App\Application\Router\Redirect;
use App\Application\Helpers\Random;
use App\Application\Auth\Auth;
use App\Services\UserService;

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
            http_response_code(412);
            die ('invalid email');
        }

        if ($user->find('email', $email)) {
            http_response_code(409);
            die ('this email already taken');
        }

        if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
            http_response_code(412);
            die('weak_password');
        }

        echo $this->service->register($request);
    }

    public function login(Request $request)
    {
        $this->service->login($request);
    }

    public function logout()
    {
        unset($_COOKIE[Auth::getTokenColumn()]);
        setcookie(Auth::getTokenColumn(), NULL);
        Redirect::to('/login');
    }
}

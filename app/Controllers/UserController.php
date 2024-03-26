<?php

namespace App\Controllers;


use Symfony\Component\HttpFoundation\Request;


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
        $this->service->register($request);
    }

    public function login(Request $request)
    {
        $this->service->login($request);
    }

    public function logout()
    {
        $this->service->logout();
    }

    public function feed() {
        http_response_code(200);
    }
}

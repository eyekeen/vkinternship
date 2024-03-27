<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;

use App\Application\Helpers\ResponseHelper;
use App\Application\Helpers\UserHelper;
use App\Application\Helpers\TokenHelper;

use App\Models\User;


class UserService
{
    public function register(Request $request): void
    {
        $user = new User();

        $email = $request->request->get('email');
        $password = $request->request->get('password');

        // validation email and password before register
        UserHelper::checkData($user, $email, $password);

        $user->setEmail($email);
        $user->setPassword($password);


        $user->store();

        $user_id = $user->find('email', $user->getEmail())->getId();

        ResponseHelper::created(['user_id' => $user_id,'password_check_status' => 'good']);
    }

    public function login(Request $request): void
    {
        UserHelper::checkLogin($request);
    }

    public function logout(): void
    {
        $auth_token = getallheaders()['Authorization'] ?? false;

        $user = TokenHelper::tokenValid($auth_token);

        $user->update(['token' => null]);

        ResponseHelper::ok(['msg' => 'user logout']);

    }
}

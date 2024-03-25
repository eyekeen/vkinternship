<?php

namespace App\Services;

use App\Application\Request\Request;
use App\Models\User;
use App\Application\Router\Redirect;

/**
 * Description of UserService
 *
 * @author tarum2
 */
class UserService {
    public function register(Request $request): string|bool {
        $user = new User();

        $user->setEmail($request->post('email'));
        $user->setPassword($request->post('password'));

        // dd($user);

        $user->store();

        $user_id = $user->find('email', $user->getEmail())->getId();

        http_response_code(200);
        return json_encode([
            'user_id' => $user_id,
            'password_check_status' => 'good'
        ]);
    }
}

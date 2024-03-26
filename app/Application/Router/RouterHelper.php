<?php

namespace App\Application\Router;

use Symfony\Component\HttpFoundation\Request;


trait RouterHelper {

    protected static function filter(array $routes, string $type): array {
        return array_filter($routes, function ($route) use ($type) {
            return $route['type'] === $type;
        });
    }

    protected static function controller(array $route) {
        $controller = new $route['controller']();
        $method = $route['method'];
        $request = Request::createFromGlobals();
        $controller->$method($request);
    }
}

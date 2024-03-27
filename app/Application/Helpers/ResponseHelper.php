<?php

namespace App\Application\Helpers;

use Symfony\Component\HttpFoundation\Response;

class ResponseHelper
{
    public static function ok(array $data = [])
    {
        $response = new Response(
            json_encode($data),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
        $response->send();
        exit;
    }
    public static function badRequst(array $data = [])
    {
        $response = new Response(
            json_encode($data),
            Response::HTTP_BAD_REQUEST,
            ['content-type' => 'application/json']
        );
        $response->send();
        exit;
    }
    public static function unauthorized(array $data = [])
    {
        $response = new Response(
            json_encode($data),
            Response::HTTP_UNAUTHORIZED,
            ['content-type' => 'application/json']
        );
        $response->send();
        exit;
    }
    public static function created(array $data = [])
    {
        $response = new Response(
            json_encode($data),
            Response::HTTP_CREATED,
            ['content-type' => 'application/json']
        );
        $response->send();
        exit;
    }
    public static function conflict(array $data = [])
    {
        $response = new Response(
            json_encode($data),
            Response::HTTP_CONFLICT,
            ['content-type' => 'application/json']
        );
        $response->send();
        exit;
    }
}
<?php

namespace App\Application\Response;

class Response
{
    public static function json(int $status_code = 200, array $data): void
    {
        $json = json_encode($data);
        if (!$json) {
            http_response_code(500);
            echo 'invalid json';    
        }

        http_response_code($status_code);
        echo $json;
    }
}
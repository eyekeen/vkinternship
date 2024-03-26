<?php

return [
    'model' => \App\Models\User::class,
    'token_column' => $_ENV['TOKEN_COLUMN'] ?? 'token',
    'jwt_secret' => $_ENV['JWT_SECRET'] ?? 'I5rgJHzc9+y4gbB3XoDqpya5WTdNTWga/rrSyKuC2+JFRg8Xnvx1ZVqQR1JtfnOT',
    'jwt_expires_in' => $_ENV['JWT_EXPIRES_IN'] ?? 90 //later
];
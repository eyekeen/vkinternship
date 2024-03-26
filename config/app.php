<?php

return [
    'exception_view' => $_ENV['EXCEPTION_VIEW'] ?? 'app/exception',
    'lang' => $_ENV['LANG'] ?? 'ru',
    'debug' => $_ENV['DEBUG'] ?? true,
];
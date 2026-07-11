<?php
return [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'dbname' => getenv('DB_NAME') ?: 'quickevents',
    'user' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASS') !== false ? getenv('DB_PASS') : '',
    'port' => (int) (getenv('DB_PORT') ?: 3306),
    'charset' => getenv('DB_CHARSET') ?: 'utf8mb4'
];

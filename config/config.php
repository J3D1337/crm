<?php
// Database configuration
return [
    'database' => [
        'host' => getenv('MYSQL_HOST') ?: 'localhost',  // Database host (use 'db' in Docker)
        'port' => getenv('MYSQL_PORT') ?: '3306',        // MySQL default port
        'dbname' => getenv('MYSQL_DATABASE') ?: 'crm',   // Database name
        'username' => getenv('MYSQL_USER') ?: 'root',    // Database username
        'password' => getenv('MYSQL_PASSWORD') ?: 'secret', // Database password
        'charset' => 'utf8mb4',                           // Database charset
    ]
];

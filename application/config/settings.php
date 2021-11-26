<?php

$applicationEnvironment = getenv('APP_ENV') ?? 'dev';

// Error reporting for development
error_reporting($applicationEnvironment === 'dev' ? E_ALL : 0);
ini_set('display_errors', $applicationEnvironment === 'dev' ? '1' : 0);

// Timezone
date_default_timezone_set('Europe/Kiev');

// Settings
$settings = [];

// Path settings
$settings['root'] = dirname(__DIR__);

// Error Handling Middleware settings
$settings['error'] = [
    // Should be set to false in production
    'display_error_details' => true,

    // Parameter is passed to the default ErrorHandler
    // View in rendered output by enabling the "displayErrorDetails" setting.
    // For the console and unit tests we also disable it
    'log_errors' => true,

    // Display error details in error log
    'log_error_details' => true,
];

// Database settings
$settings['db'] = [
    'driver' => 'mysql',
    'host' => getenv('MYSQL_HOST'),
    'username' => getenv('MYSQL_USER'),
    'database' => getenv('MYSQL_DATABASE'),
    'password' => getenv('MYSQL_PASSWORD'),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'flags' => [
        // Turn off persistent connections
        PDO::ATTR_PERSISTENT => false,
        // Enable exceptions
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // Emulate prepared statements
        PDO::ATTR_EMULATE_PREPARES => true,
        // Set default fetch mode to array
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Set character set
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci'
    ],
];

$settings['commands'] = [
    \App\Console\UserGenerateCommand::class,
];

return $settings;
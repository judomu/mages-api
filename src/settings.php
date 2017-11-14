<?php

if (file_exists(__DIR__ . '/../config.php')) {
  require_once(__DIR__ . '/../config.php');
} else {
  die('Base config file "config.php" missing.');
}

return [
  'settings' => [
    'displayErrorDetails' => true, // set to false in production
    'addContentLengthHeader' => false, // Allow the web server to send the content-length header

    // Renderer settings
    'renderer' => [
      'template_path' => __DIR__ . '/../templates/',
    ],

    // Monolog settings
    'logger' => [
      'name' => 'slim-app',
      'path' => __DIR__ . '/../logs/app.log',
      'level' => \Monolog\Logger::DEBUG,
    ],

    // Mysql settings
    'db' => [
      'driver' => DB_DRIVER,
      'host' => DB_HOST,
      'port' => DB_PORT,
      'database' => DB_NAME,
      'username' => DB_USER,
      'password' => DB_PASSWORD,
      'charset' => DB_CHARSET,
      'collation' => 'utf8_unicode_ci',
      'prefix' => '',
    ],

    // Files settings
    'files' => [
      'public_folder' => 'uploads',
      'folder_path' => __DIR__ .'/../public/uploads/'
    ]
  ],
];

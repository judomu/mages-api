<?php

if (file_exists(__DIR__ . '/../config.php')) {
  require_once(__DIR__ . '/../config.php');
} else {
  die('Base config file "config.php" missing.');
}

return [
  "paths" => [
    "migrations" => "db/migrations",
    "seeds" => "db/seeds"
  ],

  "environments" => [
    "default_migration_table" => "phinxlog",
    "default_database" => "production",
    "production" => [
      "adapter" => DB_DRIVER,
      "host" => DB_HOST,
      "name" => DB_NAME,
      "user" => DB_USER,
      "pass" => DB_PASSWORD,
      "port" => DB_PORT,
      "charset" => DB_CHARSET
    ]
  ]
];
?>

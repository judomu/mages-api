<?php

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
  $settings = $c->get('settings')['renderer'];
  return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
  $settings = $c->get('settings')['logger'];
  $logger = new Monolog\Logger($settings['name']);
  $logger->pushProcessor(new Monolog\Processor\UidProcessor());
  $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
  return $logger;
};

// Service factory for the ORM
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);

$capsule->setAsGlobal();
$capsule->bootEloquent();
$capsule->getDatabaseManager()->enableQueryLog();

$container['db'] = $capsule;

// Business services
$container['playerApplicationService'] = function ($cs) {
  return new \Mages\PlayerApplicationService($cs);
};

$container['teamApplicationService'] = function ($cs) {
  return new \Mages\TeamApplicationService($cs);
};

$container['gameApplicationService'] = function ($cs) {
  return new \Mages\GameApplicationService($cs);
};


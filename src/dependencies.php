<?php
// DIC configuration

$container = $app->getContainer();

// monolog
$container['logger'] = function ($container) {
    $settings = $container->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container[\ApiKeep\Controller\ApiController::class] = function ($container) {
    $logger = $container->get('logger');
    return new \ApiKeep\Controller\ApiController($logger);
};

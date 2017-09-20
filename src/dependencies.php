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

$container['errorHandler'] = function ($container) {
    return function ($request, $response, $e) use ($container) {
        // retrieve logger from $container here and log the error
        $response->getBody()->rewind();

        $logger = $container->get('logger');
        $pattern = "Message : %s \n Trace: %s";
        $errorMessage = sprintf($pattern, $e->getMessage(), $e->getTraceAsString());
        $logger->error($errorMessage);

        return $response->withStatus(500)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode([
                'status' => 'NOK',
                'message' => 'Something went wrong ;(, please,  try again later'
            ]));
    };
};

$container['phpErrorHandler'] = function ($container) {
    return $container['errorHandler'];
};


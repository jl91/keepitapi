<?php
// Routes

$container = $app->getContainer();
$app->get('/', $container->get(ApiKeep\Controller\ApiController::class));

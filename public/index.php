<?php


require __DIR__ . '/../vendor/autoload.php';

use \Slim\Factory\AppFactory;

$app = AppFactory::create();


$app->get('/', function ($request, $response, $args) {
    $response->getBody()->write("Hello, world!");
    return $response;
});

$app->run();
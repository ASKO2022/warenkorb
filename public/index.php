<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Twig\Loader\FilesystemLoader;

$app = AppFactory::create();

$loader = new FilesystemLoader(__DIR__ . '/../resources/views');
$twig = new Twig($loader);

$app->add(TwigMiddleware::create($app, $twig));

$app->get('/index', function ($request, $response, $args) use ($twig) {
    return $twig->render($response, 'index.html.twig', ['name' => 'World']);
});

$app->run();

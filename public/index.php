<?php

session_start();

use Cart\BackendControllers\AdminDashboardController;
use Cart\BackendControllers\AdminLoginController;
use Cart\Config\TwigConfig;
use Cart\Controllers\DashboardController;
use Cart\Controllers\LoginController;
use Cart\Controllers\LogoutController;
use Cart\Controllers\ProductController;
use Cart\Controllers\RegisterController;
use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Views\TwigMiddleware;


require __DIR__ . '/../vendor/autoload.php';

$container = new Container();

$container->set('view', function () {
    return TwigConfig::create();
});

$app = AppFactory::createFromContainer($container);
$app->add(TwigMiddleware::createFromContainer($app, 'view'));

$app->get('/admin',AdminLoginController::class . ':showAdminLoginForm');
$app->get('/admin/dashboard',AdminDashboardController::class . ':showDashboard');


$app->get('/',DashboardController::class . ':showDashboard');

$app->get('/login', LoginController::class . ':showLoginForm');
$app->post('/login', LoginController::class . ':login');


$app->get('/products', ProductController::class . ':getAllProducts');
$app->post('/products', ProductController::class . ':addProduct');
$app->post('/products/update', ProductController::class . ':updateProduct');
$app->post('/products/delete/{id}', ProductController::class . ':deleteProduct');


$app->get('/register', RegisterController::class . ':showRegisterForm');
$app->post('/register', RegisterController::class . ':register');


$app->post('/logout', LogoutController::class . ':logout');


$app->run();

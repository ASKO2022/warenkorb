<?php

namespace Cart\Controllers;


use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class DashboardController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function showDashboard(Request $request, Response $response, $args)
    {
        $view = $this->container->get('view');
        return $view->render($response, 'dashboard.html.twig');
    }
}
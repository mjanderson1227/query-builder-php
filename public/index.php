<?php

include '../vendor/autoload.php';

use App\Controller\HomeController;
use Database\DatabaseManager;
use Framework\Request;
use Framework\Router;
use State\Environment;

Environment::parse('../.env');

DatabaseManager::connect(
    Environment::var('DB_URL'),
    Environment::var('DB_USER'),
    Environment::var('DB_PASSWORD')
);

$router = new Router();

// Build a request from the php information see Request.php for more information.
$request = new Request();

$home = new HomeController();

// Set up routes.
$router->get('/', [$home, 'show']);

$router->get('/create', [$home, 'showCreate']);
$router->post('/create/submit', [$home, 'handleCreate']);

$router->get('/edit', [$home, 'showUpdate']);
$router->post('/edit/submit', [$home, 'handleUpdate']);

$router->get('/delete', [$home, 'showDelete']);
$router->post('/delete/submit', [$home, 'handleDelete']);

// Dispatch the request to the router.
$router->dispatch($request);

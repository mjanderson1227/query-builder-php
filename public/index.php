<?php

include '../vendor/autoload.php';

use App\Controller\HomeController;
use App\Controller\QueryController;
use App\Controller\StaticAssetsController;
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

$api = new QueryController();

// Set up routes.
$router->get('/', [new HomeController(), 'show']);
$router->get('/static', [new StaticAssetsController(), 'serve']);
$router->get('/api/equipment/show', [$api, 'show']);
$router->post('/api/equipment/create', [$api, 'create']);
$router->post('/api/equipment/update', [$api, 'update']);
$router->post('/api/equipment/delete', [$api, 'delete']);

// Dispatch the request to the router.
$router->dispatch($request);

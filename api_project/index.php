<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();
$app->setBasePath('/api_project');
$app->addErrorMiddleware(true, true, true);

require __DIR__ . '/db_con.php';
require __DIR__ . '/login.php';
require __DIR__ . '/register.php';
require __DIR__ . '/stadium.php';
require __DIR__ . '/profile.php';
require __DIR__ . '/booking.php';
require __DIR__ . '/send-email.php';

$app->options('/{routes:.+}', function (Request $request, Response $response, array $args) {
    return $response;
});

$app->add(function (Request $request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});



$app->get('/ping', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("Pong!!!");
    return $response;
});

// // เส้นทางสำหรับส่งอีเมล
// $app->post('/send-email', 'sendEmail');

$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function (Request $request, Response $response) {
    throw new HttpNotFoundException($request);
});

$app->run();

<?php

declare(strict_types=1);

use Slim\App;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Dotenv\Dotenv;
// use App\Application\Actions\User\ViewUserAction;
// use App\Application\Actions\User\ListUsersAction;
// use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Application\Controller\UsersController;

return function (App $app) {    
    $app->options('/{routes:.*}', function (ServerRequestInterface $request, Response $response) {
        return $response;
    });

    $app->get('/db-test', function (ServerRequestInterface $request, Response $response) {
        $db = $this->get(PDO::class);
        $sth = $db->prepare("SELECT * FROM users");
        $sth->execute();
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        $payload = json_encode($data);
        $response->getBody()->write($payload);
        
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/user', UsersController::class . ':getAllUsers');
};

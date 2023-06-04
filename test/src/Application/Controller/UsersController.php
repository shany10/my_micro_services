<?php

namespace App\Application\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UsersController
{
     public function getAllUsers(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
     {
          $response->getBody()->write('');
          return $response;
     }
}

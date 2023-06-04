<?php

namespace App\Application\Controller;

use Psr\Http\Message\ResponseInterface;

class TestController 
{
    public function index (ResponseInterface $response): ResponseInterface 
    {
        $response->getBody()->write("test");
        return $response;
    }
}
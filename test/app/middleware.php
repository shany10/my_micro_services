<?php

declare(strict_types=1);

use Slim\App;
use App\Application\Middleware\SessionMiddleware;

return function (App $app) {
    $app->addErrorMiddleware(true, true,  true);
    $app->add(SessionMiddleware::class);
};
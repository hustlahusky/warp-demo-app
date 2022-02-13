<?php

declare(strict_types=1);

use App\Infrastructure\Http\Controllers;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return static function (App $app): void {
    $app->get('/', Controllers\HomepageController::class);

    $app->group('/user/{userId}', function (Group $group): void {
        $group->get('', Controllers\FetchUserController::class);
        $group->get('/pets', Controllers\ListUserPetsController::class);
    });
};

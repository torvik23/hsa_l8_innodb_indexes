<?php

declare(strict_types=1);

use App\Application\Action\HomeAction;
use App\Application\Action\UserGenerateAction;
use App\Application\Action\UserReadAction;
use Slim\App;

return function (App $app) {
    $app->get('/', HomeAction::class)->setName('home');
    $app->get('/user', UserReadAction::class)->setName('user');
    $app->get('/user/generate', UserGenerateAction::class);
};
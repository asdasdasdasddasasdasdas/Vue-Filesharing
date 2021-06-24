<?php

return function (\Slim\App $app) {
    $c = $app->getContainer();

    $c->set(\Test\helpers\AuthHelper::class, function () use ($c) {

        return new \Test\helpers\AuthHelper($c->get("em")->getRepository(\Test\models\User::class));
    });

    $c->set("view", function () use ($c, $app) {


        $twig = new \Slim\Views\Twig (new Twig\Loader\FilesystemLoader("../src/templates"));
        $twig->getEnvironment()->addGlobal("auth", $c->get(\Test\helpers\AuthHelper::class));

        return $twig;

    });

    $c->set(\Test\handlers\NotFoundHandler::class, function () use ($c, $app) {

        return new \Test\handlers\NotFoundHandler($c->get("view"), $app->getCallableResolver(), $app->getResponseFactory());
    });

    $c->set("validator", function () use ($c) {

        return new \Test\validator\Validator($c->get("em"));
    });

    $c->set(\Test\controllers\FileController::class, function () use ($c) {

        return new \Test\controllers\FileController($c->get("view"), $c->get("em"), $c->get("validator"));
    });


    $c->set(\Test\controllers\AuthController::class, function () use ($c) {

        return new \Test\controllers\AuthController($c->get("view"), $c->get("validator"), $c->get("em"));
    });
    $c->set(\Test\controllers\FilesController::class, function () use ($c) {

        return new \Test\controllers\FilesController($c->get("view"), $c->get("em"));
    });

    $c->set(\Test\controllers\ProfileController::class, function () use ($c) {

        return new \Test\controllers\ProfileController($c->get("view"), $c->get("em"));
    });
    $c->set(\Test\controllers\UserController::class, function () use ($c) {

        return new \Test\controllers\UserController($c->get("em"), $c->get("validator"));
    });
    $c->set(\Test\controllers\CommentController::class, function () use ($c) {
        return new \Test\controllers\CommentController($c->get("em"));
    });
    $c->set(\Test\controllers\HomeController::class, function () use ($c) {
        return new \Test\controllers\HomeController($c->get("view"),$c->get("em"));
    });
};
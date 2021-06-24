<?php
return function (\Slim\App $app){

    $c = $app->getContainer();

    $app->addRoutingMiddleware();
    $errorMiddleware = $app->addErrorMiddleware(true,true,true);
    //$errorMiddleware->setDefaultErrorHandler( new \Test\handlers\NotFoundHandler($c->get("view"),$app->getCallableResolver(),$app->getResponseFactory()));
    $app->add(\Slim\Views\TwigMiddleware::createFromContainer($app));
};
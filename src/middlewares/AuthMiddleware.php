<?php


namespace Test\middlewares;


use Test\helpers\AuthHelper;

class AuthMiddleware
{
    public function __invoke($request, $handler)
    {
        if (AuthHelper::getAuthUser()) {
            return $handler->handle($request);
        } else {
            $response = new \Slim\Psr7\Response();
            $response = $response->withHeader("Location", "/");

            return $response;
        }
    }
}
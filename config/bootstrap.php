<?php

use Test\controllers\FileController;

require '../vendor/autoload.php';

$c = new \DI\Container();
require "defines.php";
(require "database.php")($c);
\Slim\Factory\AppFactory::setContainer($c);
$app = \Slim\Factory\AppFactory::create();
(require "../config/container.php")($app);
(require "../config/middleware.php")($app);
(require "../config/routes.php")($app);
return $app;
<?php

return function ($c) {
    $conn = array(
        'driver' => 'pdo_mysql',
        'user' => 'root',
        'password' => 'root',
        'dbname' => 'filesharing',
    );
    $isDevMode = true;
    $proxyDir = null;
    $cache = null;
    $useSimpleAnnotationReader = false;




    $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/../src/models"), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);
    $em = \Doctrine\ORM\EntityManager::create($conn, $config);
    $c->set("em",$em);
};
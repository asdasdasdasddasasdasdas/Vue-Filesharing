<?php


namespace Test\helpers;


use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Slim\Psr7\Response;
use Test\models\User;

class AuthHelper
{

    private static $repository;
    private $cookie;

    public function __construct(EntityRepository $repository)
    {
        self::$repository = $repository;

    }


    public static function getAuthUser(): ?User
    {

        $cookie = $_COOKIE["hash"] ?? "";
        $user = self::$repository->getUserByHash($cookie);
        return $user ? $user[0] : null;
    }

    public static function setAuth($response, $hash): Response
    {

        if (isset($hash)) {
            return $response->withAddedHeader("Set-Cookie", "hash=" . $hash . "; Max-Age=" . (60 * 60 * 24 * 30) . "; Path=/");
        } else {
            return $response;
        }
    }

    public static function login($data, $response): Response
    {
        $hash = self::$repository->login($data)[0]["hash"];

        return self::setAuth($response, $hash);
    }
}
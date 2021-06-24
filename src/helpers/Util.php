<?php


namespace Test\helpers;


class Util
{


    public static function slug(int $integer) :string
    {
       return bin2hex(random_bytes($integer));
    }
}
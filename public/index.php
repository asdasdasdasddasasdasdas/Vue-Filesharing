<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

function dd($t = ' ')
{
    if (isset($t)) {
        echo "<pre>";
        var_dump($t);
        echo "</pre>";

    }
    die();
}

function d($t = ' ')
{
    if (isset($t)) {
        echo "<pre>";
        var_dump($t);
        echo "</pre>";
    }

}


(require "../config/bootstrap.php")->run();
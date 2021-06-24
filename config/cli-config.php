<?php
$em = (require_once "bootstrap.php")->getContainer()->get("em");

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($em);

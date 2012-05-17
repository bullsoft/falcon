<?php
define("ROOT", __DIR__);
define("FRAMEWORK", ROOT . DIRECTORY_SEPARATOR . "Framework");
define("WEB", FRAMEWORK . DIRECTORY_SEPARATOR . "Web");
define("MODEL", FRAMEWORK . DIRECTORY_SEPARATOR . "Model");

require_once(ROOT . DIRECTORY_SEPARATOR . "Framework"
             . DIRECTORY_SEPARATOR ."Bootstrap.php");

$bootstrap = new Bootstrap();

$bootstrap->execWeb();

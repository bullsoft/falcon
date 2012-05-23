<?php
defined("ROOT") || define("ROOT", dirname(__DIR__));
define("BULL_CONFIG_MODE", "dev");
require ROOT . "/Tool/Bootstrap.php";
$bootstrap = new Bootstrap();
$bootstrap->execCli();
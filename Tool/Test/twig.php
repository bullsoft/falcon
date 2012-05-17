<?php

define("ROOT", dirname(dirname(__DIR__)));
define("BULL_CONFIG_MODE", "default");

require ROOT."/Framework/Bootstrap.php";
$bootstrap = new Bootstrap();
$bootstrap->execCli();


$view = new Bull_View_Twig();
$view->name = "Roy Gu";
// echo $view->renderString('Hello {{ name }}!');

$view->displayString('Hello {{name}}!');
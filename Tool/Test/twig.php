<?php
define("ROOT", dirname(dirname(__DIR__)));
require ROOT. '/Tool/cli.php';

$view = new Bull_View_Twig();
$view->name = "Roy Gu";
// echo $view->renderString('Hello {{ name }}!');

$view->displayString('Hello {{name}}!');
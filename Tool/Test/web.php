<?php

define("ROOT", dirname(dirname(__DIR__)));
define("BULL_CONFIG_MODE", "default");

require ROOT."/Framework/Bootstrap.php";
$bootstrap = new Bootstrap();
$bootstrap->execCli();


/* $view = new Bull_View_Twig(); */
/* $view->name = "Roy Gu"; */
/* echo $view->renderString('Hello {{ name }}!'); */

/* $view->displayString('Hello {{name}}!'); */

$params = array(
    'action' => 'test',
    'format' => '.html',
    'noun'   => 'world',
);

$page = new Framework_Web_Test(new Bull_Web_Context($GLOBALS), $params);

$page->setView(new Bull_View_Twig());

$response = $page->exec();
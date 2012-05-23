<?php
define("ROOT", dirname(dirname(__DIR__)));
require ROOT. '/Tool/cli.php';

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
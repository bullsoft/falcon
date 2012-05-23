<?php
define("ROOT", dirname(dirname(__DIR__)));
require ROOT. '/Tool/cli.php';

$config = new Bull_Parse_Php();
$config->load("Tool/Test/config.php");
var_dump($config->get("db.default"));
var_dump($config->model);



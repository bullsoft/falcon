<?php

define("ROOT", dirname(dirname(__DIR__)));
define("BULL_CONFIG_MODE", "default");

require ROOT. "/Tool/Bootstrap.php";
$bootstrap = new Bootstrap();
$bootstrap->execCli();

$config = Bull_Di_Container::get('config');

// var_dump($config->db->default->dsn);

// var_dump($config->get('db.default.dsn'));

// var_dump($config->model->directory);

$test1 = $config->get('class.bull_db.setters.setName');
$test = $config->db->default->get();

var_dump($test1, $test);
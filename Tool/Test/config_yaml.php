<?php

define("ROOT", dirname(dirname(dirname(__FILE__))));
define("BULL_CONFIG_MODE", "dev");

require ROOT. "/Tool/Bootstrap.php";
$bootstrap = new Bootstrap();
$bootstrap->execCli();

$config = new Bull_Parse_Yaml();

$config->load("Tool/Test/config.yaml");

var_dump($config->db->default->dsn);

var_dump($config->get('db.default.dsn'));

var_dump($config->use_namespace);



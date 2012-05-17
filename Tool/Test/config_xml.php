<?php

define("ROOT", dirname(dirname(dirname(__FILE__))));
define("BULL_CONFIG_MODE", "dev");

require ROOT. "/Tool/Bootstrap.php";
$bootstrap = new Bootstrap();
$bootstrap->execCli();

$config = new Bull_Parse_Xml();

$config->load("Tool/Test/config.xml");

var_dump($config->get("db.default"));

var_dump($config->db->default);



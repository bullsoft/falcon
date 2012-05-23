<?php
define("ROOT", dirname(dirname(dirname(__FILE__))));
require ROOT. '/Tool/cli.php';

$config = new Bull_Parse_Xml();

$config->load("Tool/Test/config.xml");

var_dump($config->get("db.default"));

var_dump($config->db->default);



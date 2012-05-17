<?php

define("ROOT", dirname(dirname(dirname(__FILE__))));
define("BULL_CONFIG_MODE", "default");

require ROOT . "/Framework/Bootstrap.php";
$bootstrap = new Bootstrap();
$bootstrap->execCli();

$db = Bull_Di_Container::newInstance("Bull_Db_Front");

$db->setServer("dbtemp")->setServer("db");

$sql = $db->setName('db')->query('select user_id, user_type, group_id from phpbb_users limit 1');
var_dump($sql->fetchAll());

$sql = $db->setName('dbtemp')->query('select ID, user_login from wp_users limit 1'); 
var_dump($sql->fetchAll());

$sql = $db->setName('db')->query('select user_id, user_type, group_id from phpbb_users limit 1');
var_dump($sql->fetchAll());


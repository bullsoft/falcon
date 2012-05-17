<?php

$db = array();

$db['default'] = array('adapter' => 'mysql',
                       'dsn' => array('dbname' => 'Dr_Cradle3', 'charset' => 'utf8'),);

$db['master']['m0'] = array('dsn' => array('host' => '127.0.0.1', 'port' => 3306),
                            'username' => 'root', 'password' => '123456');

$model = "UserModel";
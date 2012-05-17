<?php

define("ROOT", dirname(dirname(__DIR__)));
define("BULL_CONFIG_MODE", "defalut");
require ROOT . "/Tool/Bootstrap.php";
$bootstrap = new Bootstrap();
$bootstrap->execCli();

$str = 'https://example.com/pls/portal30/PORTAL30.wwpob_page.changetabs?p_back_url=http%3A%2F%2Fexample.com%2Fservlet%2Fpage%3F_pageid%3D360%2C366%2C368%2C382%26_dad%3Dportal30%26_schema%3DPORTAL30&foo=bar#hello';

$url = &new Bull_Net_Url($str);

echo $url->protocol;
echo PHP_EOL;
echo $url->user;
echo PHP_EOL;
echo $url->password;
echo PHP_EOL;
echo $url->host;
echo PHP_EOL;
echo $url->port;
echo PHP_EOL;
echo $url->path;
echo PHP_EOL;
print_r($url->query);
echo PHP_EOL;
echo $url->fragment;
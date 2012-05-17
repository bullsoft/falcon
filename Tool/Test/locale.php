<?php

define("ROOT", dirname(dirname(__DIR__)));
define("BULL_CONFIG_MODE", "default");

require ROOT."/Framework/Bootstrap.php";
$bootstrap = new Bootstrap();
$bootstrap->execCli();

$locale = Bull_Di_Container::newInstance('Bull_Util_Locale');

echo $locale->get('HELLO_WORLD', 'Bull_Filter_Manager', array('顾伟刚'));
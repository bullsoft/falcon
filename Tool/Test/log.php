<?php

define("ROOT", dirname(dirname(__DIR__)));
define("BULL_CONFIG_MODE", "product");

require ROOT."/Tool/Bootstrap.php";
$bootstrap = new Bootstrap();
$bootstrap->execCli();


// $log = new Bull_Log_File(ROOT."/Framework/Tmp/log/bull.log");

$log = new Bull_Log_Echo();
$log->save("BULL_TEST", "debug", "here is a test for debug logs.");
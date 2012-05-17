<?php

define("ROOT", dirname(dirname(dirname(__FILE__))));

require ROOT."/Bull/Util/splClassLoader.php";

$classloader = new splClassLoader(null, ROOT);
$classloader->register();

$locale = new Bull_Util_Locale('zh_CN');

echo $locale->get('HELLO_WORLD');

echo PHP_EOL;


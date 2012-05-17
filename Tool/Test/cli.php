<?php
define("ROOT", dirname(dirname(__DIR__)));
define("BULL_CONFIG_MODE", "defalut");
require ROOT . "/Tool/Bootstrap.php";
$bootstrap = new Bootstrap();
$bootstrap->execCli();

$options = array(
    'foo_bar' => array(
        'long'    => 'foo-bar',
        'short'   => 'f',
        'param'   => Bull_Cli_Option::PARAM_REQUIRED,
        'multi'   => false,
        'default' => null,
    ),        
);

$cli = new Bull_Cli_Front($options);

var_dump($cli->getParams());


$cli->getStdio()->out("The value of -f/--foo-bar is ");
echo $cli->getOpt()->foo_bar;
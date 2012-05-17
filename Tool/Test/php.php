<?php
define("ROOT", dirname(dirname(__DIR__)));
define("BULL_CONFIG_MODE", "product");

require ROOT . "/Tool/Bootstrap.php";
$bootstrap = new Bootstrap();
$bootstrap->execCli();

$ini = array(
    'error_reporting' =>  E_ALL | E_STRICT,
    'error_display'   =>  1,
    'html_errors'     =>  0,
);

$options = array(
    'config' => array(
        'long'    => 'config',
        'short'   => 'c',
        'param'   => Bull_Cli_Option::PARAM_REQUIRED,
        'multi'   => false,
        'default' => null,
    ),
    'table' => array(
        'long'    => 'table',
        'short'   => 't',
        'param'   => Bull_Cli_Option::PARAM_REQUIRED,
        'multi'   => false,
        'default' => '*',
    ),
    'model' => array(
        'long'    => 'model',
        'short'   => 'm',
        'param'   => Bull_Cli_Option::PARAM_OPTIONAL,
        'multi'   => false,
        'default' => null,
    ),
);

$cli    = new Bull_Cli_Front($options);
$params = $cli->getOpt()->getOptionValues();

var_dump($params);

$php = new Bull_Cli_Php(array('mode' => BULL_CONFIG_MODE, 'root' => ROOT));

$code = <<<'EOF'
$name  = isset($argv[1])?$argv[1]:null;
$table = isset($argv[2])?$argv[2]:null;
$model = isset($argv[3])?$argv[3]:null;

$db = Bull_Di_Container::newInstance('Bull_Db_Front');
$config = Bull_Di_Container::get('config');

$db->setServer(array($name => $config->get($name)));

$objGen = new Bull_Model_Generate($name, $db->getConnect($name), $config->system->directory, $config->model->directory);
$objGen->execute($table, $model);
    
EOF;

$php->setIniFile('/etc/httpd/php.ini')
    ->setIniArray($ini)
    ->setArgv($params)
    ->runBullCode($code);

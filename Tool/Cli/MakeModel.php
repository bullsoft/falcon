<?php
define("ROOT", dirname(dirname(__DIR__)));
require ROOT . "/Tool/cli.php";
class Tool_Cli_MakeModel extends Bull_Cli_Command
{
    protected $options = array(
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
    
    public function action()
    {
        $name  = $this->getopt->config;
        $table = $this->getopt->table;
        $model = $this->getopt->model;

        $sql_front = Bull_Di_Container::newInstance('Bull_Sql_Front');
        $config    = Bull_Di_Container::get('config');

        $sql_front->setServer(array($name => $config->get($name)));

        $objGen = new Bull_Model_Generate($name,
                                          $sql_front->getConnect($name),
                                          $config->system->directory,
                                          $config->model->directory);
        $objGen->execute($table, $model);
        $this->stdio->outln("Models Generated Successfully.");
    }
}

$command = new Tool_Cli_MakeModel();
$command->exec();

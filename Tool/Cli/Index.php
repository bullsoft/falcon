<?php
define("ROOT", dirname(dirname(__DIR__)));
define("BULL_CONFIG_MODE", "defalut");
require ROOT . "/Tool/Bootstrap.php";
$bootstrap = new Bootstrap();
$bootstrap->execCli();

class Tool_Cli_Index extends Bull_Cli_Command
{
    protected $options = array(
        'foo_bar' => array(
            'long'    => 'foo-bar',
            'short'   => 'f',
            'param'   => Bull_Cli_Option::PARAM_REQUIRED,
            'multi'   => false,
            'default' => null,
        ),        
    );
    
    public function action()
    {
        foreach ($this->params as $key => $val) {
            $this->stdio->outln("Param $key is '$val'.");
        }
        
        $this->stdio->out("The value of -f/--foo-bar is ");
        $this->stdio->outln($this->getopt->foo_bar);
        /* $this->stdio->outln('Hello World!'); */
        /* $this->stdio->out('Please enter some text: '); */
        /* $input = $this->stdio->in(); */
        /* $this->stdio->errln('Input was ' . $input); */
    }
}    


$index = new Tool_Cli_Index();
$index->exec();
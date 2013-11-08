<?php
final class Bootstrap
{
    protected $config      = null;
    protected $di          = null;
    protected $application = null;
    protected $loader      = null;

    // 运行环境
    protected $env = "dev";
        
    public function __construct()
    {
        $env = get_cfg_var("phalcon.env");
        if ($env) {
            $this->env = $env;
        }
        define("PHALCON_ENV", $this->env);
        define("PHALCON_DIR", dirname(dirname(__DIR__)));
        define("PHALCON_SKELETON_DIR", dirname(__DIR__));
    }
    
    public function exec()
    {
        $this->loader = new \Phalcon\Loader();
    }
    
    public function execWeb()
    {
        $this->exec();
        define("PHALCON_RUN_ENV", "Module");
        $this->config = new \Phalcon\Config\Adapter\Ini(PHALCON_SKELETON_DIR.'/confs/'.$this->env.'.ini');
        $this->di          = new \Phalcon\DI\FactoryDefault();
        $this->application = new \Phalcon\Mvc\Application();
        $this->application->setDI($this->di);
        $this->load(PHALCON_SKELETON_DIR.'/loads/default-web.php');
        echo $this->application->handle()->getContent();
    }

    public function execCli($argv)
    {
        $this->exec();
        define("PHALCON_RUN_ENV", "Task");        
        $this->config = new \Phalcon\Config\Adapter\Ini(PHALCON_SKELETON_DIR.'/confs/'.$this->env.'.ini');
        $this->di          = new \Phalcon\DI\FactoryDefault\CLI();
        $this->application = new \Phalcon\CLI\Console();
        $this->application->setDI($this->di);
        $this->load(PHALCON_SKELETON_DIR.'/loads/default-cli.php');
        $this->application->handle($argv);
    }

    public function execMicro()
    {
        $this->exec();
        define("PHALCON_RUN_ENV", "Micro");
        $this->config = new \Phalcon\Config\Adapter\Ini(PHALCON_SKELETON_DIR.'/confs/'.$this->env.'.ini');
        $this->di          = new \Phalcon\DI\FactoryDefault\CLI();
        $this->application = new \Phalcon\Mvc\Micro();
        $this->application->setDI($this->di);
        $this->load(PHALCON_SKELETON_DIR.'/loads/default-micro.php');
        return $this->application;
        // You need handle it yourself
    }
    
    public function execCliforTest()
    {
        $this->exec();
        define("PHALCON_RUN_ENV", "Task");        
        $this->config = new \Phalcon\Config\Adapter\Ini(PHALCON_SKELETON_DIR.'/confs/'.$this->env.'.ini');
        $this->di          = new \Phalcon\DI\FactoryDefault\CLI();
        $this->application = new \Phalcon\CLI\Console();
        $this->application->setDI($this->di);
        $this->load(PHALCON_SKELETON_DIR.'/loads/default-cli.php');
    }

    public function execWebforTest()
    {
        $this->exec();
        define("PHALCON_RUN_ENV", "Module");        
        $this->config = new \Phalcon\Config\Adapter\Ini(PHALCON_SKELETON_DIR.'/confs/'.$this->env.'.ini');        
        $this->di          = new \Phalcon\DI\FactoryDefault();
        $this->application = new \Phalcon\Mvc\Application();
        $this->application->setDI($this->di);
        $this->load(PHALCON_SKELETON_DIR.'/loads/default-web.php');
    }
    
    public function load($file)
    {
        $system      = PHALCON_SKELETON_DIR;
        $loader      = $this->loader;
        $config      = $this->config;
        $application = $this->application;
        $di          = $this->di;
        return require $file;
    }
}

<?php
final class Bootstrap
{
    protected $config      = null;
    protected $di          = null;
    protected $application = null;
    protected $loader      = null;
    protected $rootPath    = null;

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
        $this->rootPath = PHALCON_SKELETON_DIR;
        $this->loader = new \Phalcon\Loader();
    }
    
    public function execWeb()
    {
        $this->exec();
        define("PHALCON_RUN_ENV", "Module");
        $this->config = new \Phalcon\Config\Adapter\Ini($this->rootPath.'/confs/'.$this->env.'.ini');
        $this->di          = new \Phalcon\DI\FactoryDefault();
        $this->application = new \Phalcon\Mvc\Application();
        // load components that you need
        $this->load($this->rootPath.'/loads/default-web.php');
        $this->application->setDI($this->di);
        echo $this->application->handle()->getContent();
    }

    public function execCli($argv)
    {
        $this->exec();
        define("PHALCON_RUN_ENV", "Task");        
        $this->config = new \Phalcon\Config\Adapter\Ini($this->rootPath.'/confs/'.$this->env.'.ini');        
        $this->di          = new \Phalcon\DI\FactoryDefault\CLI();
        $this->application = new \Phalcon\CLI\Console();
        // load components that you need
        $this->load($this->rootPath.'/loads/default-cli.php');
        $this->application->setDI($this->di);
        $this->application->handle($argv);
    }

    public function execCliforTest()
    {
        $this->exec();
        define("PHALCON_RUN_ENV", "Task");        
        $this->config = new \Phalcon\Config\Adapter\Ini($this->rootPath.'/confs/'.$this->env.'.ini');        
        $this->di          = new \Phalcon\DI\FactoryDefault\CLI();
        $this->application = new \Phalcon\CLI\Console();
        $this->load($this->rootPath.'/loads/default-cli.php');
        $this->application->setDI($this->di);
    }

    public function execWebforTest()
    {
        $this->exec();
        define("PHALCON_RUN_ENV", "Module");        
        $this->config = new \Phalcon\Config\Adapter\Ini($this->rootPath.'/confs/'.$this->env.'.ini');        
        $this->di          = new \Phalcon\DI\FactoryDefault();
        $this->application = new \Phalcon\Mvc\Application();
        $this->load($this->rootPath.'/loads/default-web.php');
        $this->application->setDI($this->di);
    }
    
    public function load($file)
    {
        $system      = $this->rootPath;
        $loader      = $this->loader;
        $config      = $this->config;
        $application = $this->application;
        $di          = $this->di;
        return require $file;
    }
}

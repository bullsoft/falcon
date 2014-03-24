<?php
final class Bootstrap
{
    protected $config      = null;
    protected $di          = null;
    protected $application = null;
    protected $loader      = null;

    // Which env are you in? product, dev, test, pre or something else
    protected $env = "dev";
    protected $confClass = null;
    
    public function __construct()
    {
        // initial, we read two configurations from php.ini
        $env = get_cfg_var("phalcon.env");
        $confType = get_cfg_var("phalcon.conf_type");
        
        if($env) { $this->env = $env; }

        if(!$confType) $confType = 'ini';
        // make sure we just accept ini/php file as our configuration
        if($confType && ($confType != 'ini' && $confType != 'php')) {
            throw new \Phalcon\Config\Exception("Phalcon config type error: [phalcon.conf_type] in php.ini");
        }
        
        // constants definition
        define("PHALCON_ENV", $this->env);
        define("PHALCON_CONF_TYPE", $confType);
        define("PHALCON_DIR", dirname(dirname(__DIR__)));
        define("PHALCON_SKELETON_DIR", dirname(__DIR__));
    }

    protected function initConf()
    {
        $confPath = PHALCON_SKELETON_DIR.'/confs/'.$this->env.'.'.PHALCON_CONF_TYPE;

        // global config file must exists
        if(!is_file($confPath)) {
            throw new \Phalcon\Config\Exception("Phalcon config file not exist, file position: {$confPath}");            
        }

        if(PHALCON_CONF_TYPE == 'ini') {
            $this->confClass = "\Phalcon\Config\Adapter\Ini";
            $this->config = new $this->confClass($confPath);
        } else if(PHALCON_CONF_TYPE == 'php') {
            $this->confClass = "\Phalcon\Config";
            $this->config = new $this->confClass(require_once($confPath));
        } else {
            throw new \Phalcon\Config\Exception("Phalcon config type error: [phalcon.conf_type] in php.ini");
        }
    }
    
    public function execWeb()
    {
        define("PHALCON_RUN_ENV", "Module");

        $this->loader = new \Phalcon\Loader();
        $this->di     = new \Phalcon\DI\FactoryDefault();

        $this->initConf();
        
        $this->application = new \Phalcon\Mvc\Application();
        $this->application->setDI($this->di);

        $this->load(PHALCON_SKELETON_DIR.'/loads/default-web.php');

        $this->di->setShared('bootstrap', $this);
        
        echo $this->application->handle()->getContent();
    }

    public function execCli($argv)
    {
        define("PHALCON_RUN_ENV", "Task");
        
        $this->loader = new \Phalcon\Loader();
        $this->di     = new \Phalcon\DI\FactoryDefault\CLI();

        $this->initConf();

        $this->application = new \Phalcon\CLI\Console();
        $this->application->setDI($this->di);

        $this->load(PHALCON_SKELETON_DIR.'/loads/default-cli.php');
        
        $this->di->setShared('bootstrap', $this);                

        $this->application->handle($argv);
    }

    public function execMicro()
    {
        define("PHALCON_RUN_ENV", "Micro");
        
        $this->loader = new \Phalcon\Loader();
        $this->di = new \Phalcon\DI\FactoryDefault\CLI();

        $this->initConf();

        $this->application = new \Phalcon\Mvc\Micro();
        $this->application->setDI($this->di);

        $this->load(PHALCON_SKELETON_DIR.'/loads/default-micro.php');

        $this->di->setShared('bootstrap', $this);                
        
        return $this->application;
        
        // ***WARNING*** You need handle it yourself
    }
    
    public function execCliforTest()
    {
        define("PHALCON_RUN_ENV", "Task");

        $this->loader = new \Phalcon\Loader();
        $this->di = new \Phalcon\DI\FactoryDefault\CLI();

        $this->initConf();        

        $this->application = new \Phalcon\CLI\Console();
        $this->application->setDI($this->di);

        $this->load(PHALCON_SKELETON_DIR.'/loads/default-cli.php');

        $this->di->setShared('bootstrap', $this);                        
        
    }

    public function execWebforTest()
    {
        define("PHALCON_RUN_ENV", "Module");

        $this->loader = new \Phalcon\Loader();
        $this->di = new \Phalcon\DI\FactoryDefault();

        $this->initConf();                

        $this->application = new \Phalcon\Mvc\Application();
        $this->application->setDI($this->di);

        $this->load(PHALCON_SKELETON_DIR.'/loads/default-web.php');

        $this->di->setShared('bootstrap', $this);
        
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

    public function getEnv()
    {
        return $this->env;
    }

    public function getConfClass()
    {
        return $this->confClass;
    }

    public function getConfObj()
    {
        return $this->config;
    }
}

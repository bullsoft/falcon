<?php
class Bootstrap
{
    /* 系统路径 */
    public $system;
    
    /* 配置模式 */
    public $mode;

    /* 构造器 */
    public function __construct()
    {
        $this->system = dirname(__DIR__);
    }
    
    public function exec()
    {
        set_include_path(get_include_path(). PATH_SEPARATOR . $this->system);

        require_once("Bull" . DIRECTORY_SEPARATOR . "Util" .
                     DIRECTORY_SEPARATOR . "SplClassLoader.php");
        
        $classloader = new SplClassLoader();
        $classloader->setMode(SplClassLoader::MODE_NORMAL);
        $classloader->add('Bull', $this->system);
        $classloader->add('Framework', $this->system);
        $classloader->add('Tool', $this->system);
        $classloader->add('Twig', $this->system. DIRECTORY_SEPARATOR . "Bull"
                          . DIRECTORY_SEPARATOR. "View");
        $classloader->register(true);
    }

    public function execWeb()
    {
        $this->exec();
        $this->mode   = empty($_ENV['BULL_CONFIG_MODE'])
            ? 'default'
            : $_ENV['BULL_CONFIG_MODE'];

        $bootstrap = $this;
        Bull_Di_Container::set('config', function () use ($bootstrap) {
                $config=new Bull_Parse_Ini();
                $config->load("Framework" . DIRECTORY_SEPARATOR
                              . "Config". DIRECTORY_SEPARATOR . $bootstrap->mode . ".ini");
                return $config;
            });
    }

    public function execCli()
    {
        $this->exec();
        $this->mode = defined("BULL_CONFIG_MODE")
            ? BULL_CONFIG_MODE
            : "default";
        
        $bootstrap = $this;
        Bull_Di_Container::set('config', function () use ($bootstrap) {
                $config=new Bull_Parse_Ini();
                $config->load("Framework" . DIRECTORY_SEPARATOR
                              . "Config". DIRECTORY_SEPARATOR . $bootstrap->mode . ".ini");
                return $config;
            });
    }
}

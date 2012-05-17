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
        $classloader->add('Twig', $this->system. DIRECTORY_SEPARATOR . "Bull"
                          . DIRECTORY_SEPARATOR. "View");
        
        $classloader->register(true);
    }

    public function execWeb()
    {
        $this->exec();
        $this->mode   = empty($_SERVER['BULL_CONFIG_MODE'])
            ? 'default'
            : $_SERVER['BULL_CONFIG_MODE'];
        
        $bootstrap = $this;
        Bull_Di_Container::set('config', function () use ($bootstrap) {
                $config = new Bull_Parse_Ini();
                $config->load("Framework" . DIRECTORY_SEPARATOR
                              . "Config". DIRECTORY_SEPARATOR . $bootstrap->mode . ".ini");
                return $config;
            });
        
        $config = Bull_Di_Container::get('config');
        
        $defautls = $config->system->defaults->get();
        
        $map = new Bull_Web_RouteMap();
        $routes = $config->get('route');
        foreach ($routes as $name => $route) {
            $map->add($name, $route['path'],
                      isset($route['detail'])?$route['detail']:null);
        }
        
        $context = new Bull_Web_Context();
        $front = Bull_Di_Container::newInstance("Bull_Web_Front", array($map, $context, $defautls));
        $response = $front->exec();
        $response->send();
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

        $config = Bull_Di_Container::get('config');
        $defautls = $config->system->defaults->get();
        
        $map = new Bull_Web_RouteMap();

        $routes = $config->get('route');
        foreach ($routes as $name => $route) {
            $map->add($name, $route['path'],
                      isset($route['detail'])?$route['detail']:null);
        }

        $context = new Bull_Cli_Context();
        $front = Bull_Di_Container::newInstance("Bull_Web_Front", array($map, $context, $defautls));
        $response = $front->exec();
        $response->send();
    }
}

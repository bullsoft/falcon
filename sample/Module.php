<?php
namespace Nexus\Sample;
use Phalcon\Config\Adapter\Ini as Config;

class Module
{
    public function registerAutoloaders()
    {
        $loader = new \Phalcon\Loader();
        $loader->registerNamespaces(array(
            'Nexus\Sample\Controllers' => __DIR__.'/controllers/',
            'Nexus\Sample\Models'      => __DIR__.'/models/',
            'Nexus\Sample\Logics'      => __DIR__.'/logics/',
        ))->register();
    }

   /**
    *
    * Register the services here to make them module-specific
    *
    */
    public function registerServices($di)
    {
        $mConfig = new Config(__DIR__.'/confs/'.PHALCON_ENV.'.ini');
        $gConfig = $di->get('config');
        $gConfig->merge($mConfig);
        $di->set('config', $gConfig);
        
        // Registering a dispatcher
        $di->set('dispatcher', function () {
                $dispatcher = new \Phalcon\Mvc\Dispatcher();
                $dispatcher->setDefaultNamespace("Nexus\Sample\Controllers\\");
                return $dispatcher;
            });
    }
}

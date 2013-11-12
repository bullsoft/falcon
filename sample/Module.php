<?php
namespace BullSoft\Sample;
use Phalcon\Config\Adapter\Ini as Config;

class Module
{
    public function registerAutoloaders()
    {
        $loader = new \Phalcon\Loader();
        $loader->registerNamespaces(array(
            'BullSoft\Sample\Controllers' => __DIR__.'/controllers/',
            'BullSoft\Sample\Models'      => __DIR__.'/models/',
            'BullSoft\Sample\Logics'      => __DIR__.'/logics/',
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
                $dispatcher->setDefaultNamespace("BullSoft\Sample\Controllers\\");
                return $dispatcher;
            });
    }
}

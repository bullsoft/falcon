<?php
namespace BullSoft\Sample;

class Task
{
    public function registerAutoloaders()
    {
        $loader = new \Phalcon\Loader();
        
        $loader->registerNamespaces(array(
            'BullSoft\Sample\Tasks'   => __DIR__.'/tasks/',
            'BullSoft\Sample\Models'  => __DIR__.'/models/',
            'BullSoft\Sample\Logics'  => __DIR__.'/logics/',
        ))->register();
    }

   /**
    *
    * Register the services here to make them module-specific
    *
    */
    public function registerServices($di)
    {
        $mConfig = include(__DIR__.'/confs/'.PHALCON_ENV.'.conf.php');
        $gConfig = $di->get('config');
        $gConfig->merge($mConfig);
        $di->set('config', $gConfig);
        
        // Registering a dispatcher
        $di->set('dispatcher', function () {
                $dispatcher = new \Phalcon\CLI\Dispatcher();
                $dispatcher->setDefaultNamespace("BullSoft\Sample\Tasks\\");
                return $dispatcher;
            });
    }

}

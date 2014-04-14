<?php
namespace BullSoft\Sample;

class Module
{
    public function registerAutoloaders()
    {
        $loader = new \Phalcon\Loader();
        $loader->registerNamespaces(array(
            'BullSoft\Sample\Controllers' => __DIR__.'/controllers/',
            'BullSoft\Sample\Models'      => __DIR__.'/models/',
            'BullSoft\Sample\Logics'      => __DIR__.'/logics/',
            'BullSoft\Sample\Plugins'     => __DIR__.'/plugins',
        ))->register();
    }

   /**
    *
    * Register the services here to make them module-specific
    *
    */
    public function registerServices($di)
    {
        // get bootstrap obj
        $bootstrap = $di->get('bootstrap');

        // get config class name
        $confClass = $bootstrap->getConfClass();

        // module config
        $mConfPath = __DIR__.'/confs/'.PHALCON_ENV.'.'.PHALCON_CONF_TYPE;
        
        if(!is_file($mConfPath)) {
            throw new \Phalcon\Config\Exception("Module config file not exist, file position: {$mConfPath}");
        }
        
        if(PHALCON_CONF_TYPE == 'ini') {
            $mConfig = new $confClass($mConfPath);
        } else if(PHALCON_CONF_TYPE == 'php') {
            $mConfig = new $confClass(require_once($mConfPath));
        }

        // global config
        $gConfig = $di->get('config');

        // merge module config and global config, module's will override global's
        $gConfig->merge($mConfig);

        // set config back
        $di->set('config', $gConfig);

        // registering a dispatcher
        $di->set('dispatcher', function () use ($di) {
            $evtManager = $di->getShared('eventsManager');
            $evtManager->attach("dispatch:beforeException", function ($event, $dispatcher, $exception) {
                switch ($exception->getCode()) {
                    case \Phalcon\Mvc\Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                    case \Phalcon\Mvc\Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                        $dispatcher->forward(array(
                            'module'     => 'sample',
                            'controller' => 'error',
                            'action'     => 'show404'
                        ));
                        return false;
                }
            });
            $acl = new \BullSoft\Sample\Plugins\Acl($di, $evtManager);
            $evtManager->attach('dispatch', $acl);
            $dispatcher = new \Phalcon\Mvc\Dispatcher();
            $dispatcher->setEventsManager($evtManager);
            $dispatcher->setDefaultNamespace("BullSoft\Sample\Controllers\\");
            return $dispatcher;
        });

        // set view with volt
        $di->set('view', function()  {
            $view = new \Phalcon\Mvc\View();
            $view->setViewsDir(__DIR__.'/views/');
            $view->registerEngines(array(
                ".volt" => function($view, $di) {
                    $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
                    $volt->setOptions(array(
                        "compiledPath"      => $di->get('config')->view->compiledPath,
                        "compiledExtension" => $di->get('config')->view->compiledExtension,
                        "compileAlways"     => (bool) $di->get('config')->application->debug
                    ));
                    $compiler = $volt->getCompiler();
                    $compiler->addExtension(new \BullSoft\Volt\Extension\PhpFunction());
                    return $volt;                    
                }
            ));
            return $view;
        });
    }
}

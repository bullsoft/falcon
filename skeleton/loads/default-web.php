<?php
/* default-web.php --- 
 * 
 * Filename: default-web.php
 * Description: 
 * Author: Gu Weigang
 * Maintainer: 
 * Created: Tue Jan 29 14:56:13 2013 (+0800)
 * Version: master
 * Last-Updated: Wed Nov 27 13:31:48 2013 (+0800)
 *           By: Gu Weigang
 *     Update #: 22
 * 
 */

/* Change Log:
 * 
 * 
 */

/* This program is part of "Baidu Darwin PHP Software"; you can redistribute it and/or
 * modify it under the terms of the Baidu General Private License as
 * published by Baidu Campus.
 * 
 * You should have received a copy of the Baidu General Private License
 * along with this program; see the file COPYING. If not, write to
 * the Baidu Campus NO.10 Shangdi 10th Street Haidian District, Beijing The Peaple's
 * Republic of China, 100085.
 */

/* Code: */

require $system."/loads/default.php";

$di->set('url', function() use ($config){
    $url = new \Phalcon\Mvc\Url();
    $url->setBaseUri($config->application->baseUri);
    return $url;
});
            
$di->set('view', function() use ($system, $config) {
    $view = new \Phalcon\Mvc\View();
    $view->setViewsDir($system.$config->application->viewsDir);
    return $view;
});

$di->setShared('session', function(){
    $session = new \Phalcon\Session\Adapter\Files();
    $session->start();
    return $session;
});

$di->set('flash', function(){
    $flash = new \Phalcon\Flash\Direct(array(
        'error'   => 'alert alert-error',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
    ));
    return $flash;
});

// register rules for router
$di->set('router', function() {
    $router = new \Phalcon\Mvc\Router(false);
    //$router->setDefaultModule("sample");
    $router->removeExtraSlashes(true);
    $router->add("/",
        array("module" => 'sample',
            "controller" => 'index',
            "action" => 'index',
        ));
    $router->add("/:module",
        array("module" => 1,
            "controller" => 'index',
            "action" => 'index',
        ));
    $router->add("/:module/:controller",
        array("module" => 1,
            "controller" => 2,
            "action" => 'index',
        ));
    $router->add("/:module/:controller/:action/:params",
        array("module" => 1,
            "controller" => 2,
            "action" => 3,
            "params" => 4
        ));
    return $router;
});

// register multi-modules
$application->registerModules($config->web_module->toArray());


/* default-web.php ends here */
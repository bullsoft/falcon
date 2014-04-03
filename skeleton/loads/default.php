<?php
/* default.php --- 
 * 
 * Filename: default.php
 * Description: Load components/services for phalcon-based systems
 * Author: Gu Weigang
 * Maintainer: 
 * Created: Tue Jan 29 14:44:41 2013 (+0800)
 * Version: master
 * Last-Updated: Mon Feb 17 18:23:00 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 77
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

mb_internal_encoding("UTF-8");

// include_once($system."/vendors/react/vendor/autoload.php");

// register global class-dirs, class-namespace and class-prefix
$loader->registerDirs(
    array(
        $system.$config->application->pluginsDir,
        $system.$config->application->libraryDir,
        $system."/vendors/",
    ))->register();

$loader->registerNamespaces(
    array(
        "BullSoft\Models"  => $system.$config->application->modelsDir,
        "BullSoft\Library" => $system.$config->application->libraryDir,
        "Imagine"          => $system."/vendors/Imagine/lib/Imagine/",
        "Buzz"             => $system."/vendors/Buzz/lib/Buzz/",
        "Wrench"           => $system."/vendors/Wrench/lib/Wrench/",
    ))->register();

// class autoloader
$di->setShared('loader', function () use ($loader) {
    return $loader;
});

// global config
$di->set('config', function () use ($config) {
    return $config;
});

// global logger
$di->set('logger', function() use ($config) {
    $filename = date('Ymd');
    $logger = new \BullSoft\Logger($config->application->logger->dir . $filename);
    $logger->setFormat($config->application->logger->format);
    return $logger;
});

$di->setShared('modelsManager', function() {
    return new Phalcon\Mvc\Model\Manager();
});

// global funciton to retrive $di
if (!function_exists("getDI")) {
    function getDI()
    {
        return \Phalcon\DI::getDefault();
    }    
}

$di->setShared('db', function(){
        return \BullSoft\Db::connect("db");
});

/* default.php ends here */

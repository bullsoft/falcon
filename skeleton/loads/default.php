<?php
/* default.php --- 
 * 
 * Filename: default.php
 * Description: Load components/services for phalcon-based systems
 * Author: Gu Weigang
 * Maintainer: 
 * Created: Tue Jan 29 14:44:41 2013 (+0800)
 * Version: 106863
 * Last-Updated: Mon Oct 14 12:40:34 2013 (+0800)
 *           By: Gu Weigang
 *     Update #: 62
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

// register global class-dirs, class-namespace and class-prefix
$loader->registerDirs(
    array(
        $system.$config->application->pluginsDir,
        $system.$config->application->libraryDir,
    ))->register();

// $loader->registerPrefixes(array("Hessian_" => $system.'/apps/library/Hessian/')) ->register();

$loader->registerNamespaces(
    array(
        "Adpipe\Models"  => $system.$config->application->modelsDir,
        "Adpipe\Library" => $system.$config->application->libraryDir,
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
    $logger = new \Logger($config->application->logger->dir . $filename);
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

/* default.php ends here */

<?php
/* default-cli.php --- 
 * 
 * Filename: default-cli.php
 * Description: 
 * Author: Gu Weigang
 * Maintainer: 
 * Created: Tue Jan 29 14:55:44 2013 (+0800)
 * Version: 106863
 * Last-Updated: Mon Oct 14 13:04:22 2013 (+0800)
 *           By: Gu Weigang
 *     Update #: 26
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

$di->set('router', function() {
        $router = new \Phalcon\CLI\Router();
        return $router;
});

$di->set('dispatcher', function() use ($di) {
        $dispatcher = new Phalcon\CLI\Dispatcher();
        $dispatcher->setDI($di);
        return $dispatcher;
});

$di->set('adpipe_ui', function(){
        return \Db::connect("adpipe_ui");
});

$di->set('exp', function(){
        return \Db::connect("experiment");
});

$application->registerModules($config->cli_module->toArray());

/* default-cli.php ends here */

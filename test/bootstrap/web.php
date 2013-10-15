<?php
/* webstrap.php --- 
 * 
 * Filename: webstrap.php
 * Description: 
 * Author: Gu Weigang
 * Maintainer: 
 * Created: Wed Jan 16 14:32:55 2013 (+0800)
 * Version: 71821
 * Last-Updated: Thu Mar 28 11:58:41 2013 (+0800)
 *           By: Gu Weigang
 *     Update #: 9
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
$dir = dirname(dirname(__DIR__));
$system = $dir . '/skeleton';
require_once $system.'/apps/Bootstrap.php';
$boostrap = new Bootstrap();
$boostrap->execWebforTest();
$GLOBALS["modules"] = array
);

function registerModule($module_name)
{
    $module = $GLOBALS['modules'][$module_name];
    require($module["path"]);
    $obj = new $module["className"];
    $obj->registerAutoloaders();
    $obj->registerServices(getDI());
}

// registerModule($module_name);

/* webstrap.php ends here */

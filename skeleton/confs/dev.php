<?php
/* dev.php --- 
 * 
 * Filename: dev.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Mon Mar 24 13:33:47 2014 (+0800)
 * Version: 
 * Last-Updated: Mon Mar 24 14:24:41 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 4
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
 * the Baidu Campus NO.10 Shangdi 10th Street Haidian District, Beijing The People's
 * Republic of China, 100085.
 */

/* Code: */

return array(
    "application" => array(
        
        "modelsDir"  => "/apps/common/models/",
        "viewsDir"   => "/apps/common/views/",
        "pluginsDir" => "/apps/plugins/",
        "libraryDir" => "/apps/library/",

        "baseUri"    => "http://tycoon.baidu.com:8088/",
        "baseUrl"    => "http://tycoon.baidu.com:8088/",

        "tmpDir"     => "/home/work/tmp/",

        "logger"     => array(
            "dir"    => "/home/work/var/log/bullsoft/",
            "format" => "[%file%:%line%][%ip%] %message%",
        ),
        
        "debug"      => false,
        "close"      => false,
    ),
    
    "cli_module" => array(

        "sample" => array(
            "className" => 'BullSoft\Sample\\' . PHALCON_RUN_ENV,
            "path"      =>  PHALCON_DIR."/sample/" . PHALCON_RUN_ENV.".php",
        ),
    ),
    
    "web_module" => array(

        "sample" => array(
            "className" => 'BullSoft\Sample\\'.PHALCON_RUN_ENV,
            "path"      =>  PHALCON_DIR."/sample/".PHALCON_RUN_ENV.".php",
        ),        
    ),

    "database" => array(
        
        // db starts here
        'db' => array(
            
            'nodes'   => 2,
            
            'charset' => 'utf8',
            
            'host'    => array(
                "10.48.31.126",
                "10.48.31.126",
            ),
            
            'port' => array(
                "8006",
                "8006",
            ),
            
            'username' => array(
                'root',
                'root',
            ),
            
            "password" => array(
                'root',
                'root',
            ),
            
            "dbname" => array(
                'bigbang',
                'bigbang',
            ),
        ),
        // db ends here

        
    ),
    
    'library' => array(
        
    ),
);


/* dev.php ends here */
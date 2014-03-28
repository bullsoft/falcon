<?php
/* dev.php --- 
 * 
 * Filename: dev.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Mon Mar 24 14:18:25 2014 (+0800)
 * Version: 
 * Last-Updated: Mon Mar 24 16:56:18 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 5
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
        "debug" => true,
        "logger" => array(
            "dir" => "/home/work/var/log/bigbang/sample/",
            "format" => "[%file%:%line%][%ip%] %message%",
        ),
    ),
    
    "view" => array(
        "compiledPath"      => "/home/work/var/compiled/sample/",
        "compiledExtension" => ".compiled",
    ),
    
    "bcs" => array(
        "host"   => 'bcs.duapp.com',
        "ak"     => 'QkAPgTkquNrTWqcbEMOOvrq7',
        "sk"     => 'zjtQ4GALm3VtTsr4wm38yRpRcSajD0ZI',
        "bucket" => 'bigbang-product-pic-1',
    ),

    "phantomjs" => array(
        "hosts" => array(
            'http://115.28.223.103:8083/',
            'http://115.28.223.103:8083/',
        ),
    ),
    
    "websocketd" => array(
        "hosts" =>  array(
            'ws://115.28.223.103:8082/',
            'ws://115.28.223.103:8082/',
        ),
    ),
);

/* dev.php ends here */
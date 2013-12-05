<?php
/* testSample.php --- 
 * 
 * Filename: testSample.php
 * Description: 
 * Author: Gu Weigang
 * Maintainer: 
 * Created: Wed Jul 24 21:37:13 2013 (+0800)
 * Version: master
 * Last-Updated: Thu Dec  5 10:37:41 2013 (+0800)
 *           By: Gu Weigang
 *     Update #: 55
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

require_once __DIR__ . "/bootstrap/cli.php";
registerTask('sample');

/**
 * @backupGlobals disabled
 */
class testSample extends PHPUnit_Framework_TestCase
{
    public function testDefault()
    {
        $expected = true;
        $actual   = false;
        $this->assertEquals($expected, $actual);        
    }
}

/* testSample.php ends here */

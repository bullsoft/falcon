<?php
/* ErrorController.php --- 
 * 
 * Filename: ErrorController.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Tue Mar  4 22:50:55 2014 (+0800)
 * Version: 
 * Last-Updated: Tue Mar  4 23:45:44 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 14
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

namespace BullSoft\Sample\Controllers;

class ErrorController extends ControllerBase
{
    public function show404Action()
    {
        echo "<h1>404, Not Found!</h1>";
        exit;
    }
}

/* ErrorController.php ends here */
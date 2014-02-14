<?php
/* PhpFunction.php --- 
 * 
 * Filename: PhpFunction.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Thu Feb 13 21:05:35 2014 (+0800)
 * Version: 
 * Last-Updated: Thu Feb 13 21:06:12 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 1
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
namespace BullSoft\Volt\Extension;
class PhpFunction
{
    /**
     * This method is called on any attempt to compile a function call
     */
    public function compileFunction($name, $arguments)
    {
        if (function_exists($name)) {
            return $name . '('. $arguments . ')';
        }
    }
}
/* Code: */



/* PhpFunction.php ends here */
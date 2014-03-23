<?php
/* Shipment.php --- 
 * 
 * Filename: Shipment.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Fri Mar  7 23:38:13 2014 (+0800)
 * Version: master
 * Last-Updated: Mon Mar 10 18:47:46 2014 (+0800)
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

namespace BullSoft\Sample\Models;

class Shipment extends \Phalcon\Mvc\Model
{
    public $id;
    public $name;
    public $slug;
    public $method;
    public $addtime;
    public $modtime;

    public function initialize()
    {
        $this->setConnectionService('db');
    }

    public function getSource()
    {
        return "shipment";
    }                            
}

/* Shipment.php ends here */
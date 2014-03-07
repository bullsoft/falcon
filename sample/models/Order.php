<?php
/* Order.php --- 
 * 
 * Filename: Order.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Fri Mar  7 22:51:13 2014 (+0800)
 * Version: 
 * Last-Updated: Sat Mar  8 00:03:07 2014 (+0800)
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
 * the Baidu Campus NO.10 Shangdi 10th Street Haidian District, Beijing The People's
 * Republic of China, 100085.
 */

/* Code: */

namespace BullSoft\Sample\Models;

class Order extends \Phalcon\Mvc\Model
{
    public $id;
    public $sn;
    public $user_id;
    public $status;
    public $price;
    public $shipment_id;
    public $customer;
    public $shipment_price;
    public $addtime;
    public $modtime;

    public function initialize()
    {
        $this->setConnectionService('db');
        $this->hasOne("user_id", "\BullSoft\Sample\Models\User", "id", array("alias" => "user"));
        $this->hasOne("shipment_id", "\BullSoft\Sample\Models\Shipment", "id", array("alias" => "shipment"));        
        $this->hasMany("id", "\BullSoft\Sample\Models\OrderDetail", "order_id", array("alias" => "order_detail"));
    }

    public function getSource()
    {
        return "order";
    }                        
}

/* Order.php ends here */
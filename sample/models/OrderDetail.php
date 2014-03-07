<?php
/* OrderDetail.php --- 
 * 
 * Filename: OrderDetail.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Fri Mar  7 22:51:13 2014 (+0800)
 * Version: 
 * Last-Updated: Fri Mar  7 23:38:53 2014 (+0800)
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

namespace BullSoft\Sample\Models;

class OrderDetail extends \Phalcon\Mvc\Model
{
    public $id;
    public $user_id;
    public $order_id;
    public $product_id;
    public $product_name;
    public $provider_id;
    public $qty;
    public $price;
    public $discount;
    public $addtime;
    public $modtime;

    public function initialize()
    {
        $this->setConnectionService('db');
        $this->hasOne("user_id", "\BullSoft\Sample\Models\User", "id", array("alias" => "user"));
        $this->hasOne("product_id", "\BullSoft\Sample\Models\Product", "id", array("alias" => "product"));
        $this->hasOne("order_id", "\BullSoft\Sample\Models\Order", "id", array("alias" => "order"));
        
    }

    public function getSource()
    {
        return "order_detail";
    }                        
}

/* OrderDetail.php ends here */


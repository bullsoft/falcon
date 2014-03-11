<?php
/* Provider.php --- 
 * 
 * Filename: Provider.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Mon Feb 10 16:18:56 2014 (+0800)
 * Version: master
 * Last-Updated: Mon Mar 10 18:28:20 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 11
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

class Provider extends \Phalcon\Mvc\Model
{
    public $id;
    public $user_id;
    public $product_id;
    public $slogan;
    public $is_new;
    public $sale_style_id = 0;
    public $price;
    public $shipment_id_1;
    public $shipment_price_1;

    public $shipment_id_2;
    public $shipment_price_2;
    
    public $shipment_id_3;
    public $shipment_price_3;
    
    public $addtime;
    public $modtime;

    public function initialize()
    {
        $this->setConnectionService('db');
        $this->hasOne("user_id", "\BullSoft\Sample\Models\User", "id", array("alias" => "user"));
        $this->hasOne("product_id", "\BullSoft\Sample\Models\Product", "id", array("alias" => "product"));
        $this->hasOne("shipment_id_1", "\BullSoft\Sample\Models\Shipment", "id", array("alias" => "shipment1"));
        $this->hasOne("shipment_id_2", "\BullSoft\Sample\Models\Shipment", "id", array("alias" => "shipment2"));
        $this->hasOne("shipment_id_3", "\BullSoft\Sample\Models\Shipment", "id", array("alias" => "shipment3"));        
    }

    public function getSource()
    {
        return "provider";
    }                    
}

/* Provider.php ends here */
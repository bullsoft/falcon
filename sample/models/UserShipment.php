<?php
/* UserShipment.php --- 
 * 
 * Filename: UserShipment.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Wed Mar 19 20:54:18 2014 (+0800)
 * Version: 
 * Last-Updated: Wed Mar 19 23:32:56 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 3
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

class UserShipment extends \Phalcon\Mvc\Model
{
    public $id;
    public $user_id;
    public $shipment_id;
    public $seq;
    public $addtime;
    public $modtime;

    public function initialize()
    {
        $this->setConnectionService('db');
        $this->hasOne("shipment_id", "\BullSoft\Sample\Models\Shipment", "id", array("alias" => "shipment"));        
    }

    public function getSource()
    {
        return "user_shipment";
    }                            
}

/* UserShipment.php ends here */
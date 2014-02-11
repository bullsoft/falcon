<?php
/* Provider.php --- 
 * 
 * Filename: Provider.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Mon Feb 10 16:18:56 2014 (+0800)
 * Version: 
 * Last-Updated: Mon Feb 10 17:09:25 2014 (+0800)
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
    public $addtime;
    public $modtime;

    public function initialize()
    {
        $this->setConnectionService('db');
        $this->hasOne("user_id", "\BullSoft\Sample\Models\User", "id", array("alias" => "user"));        
    }

    public function getSource()
    {
        return "provider";
    }                    
}

/* Provider.php ends here */
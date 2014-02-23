<?php
/* Product.php --- 
 * 
 * Filename: Product.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Mon Feb 10 16:05:29 2014 (+0800)
 * Version: master
 * Last-Updated: Sun Feb 23 23:07:40 2014 (+0800)
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

class Product extends \Phalcon\Mvc\Model
{
    public $id;
    public $name;
    public $image_url;
    public $more_image_urls;
    public $description;
    public $price;
    public $from;
    public $user_id;
    public $likeit;
    public $addtime;
    public $modtime;
    
    public function initialize()
    {
        $this->setConnectionService('db');
        $this->hasOne("user_id", "\BullSoft\Sample\Models\User", "id", array("alias" => "user"));
        $this->hasMany("id", "\BullSoft\Sample\Models\Provider", "product_id", array("alias" => "provider"));
    }

    public function getSource()
    {
        return "product";
    }                    
}
    

/* Product.php ends here */
<?php
/* Wishlist.php --- 
 * 
 * Filename: Wishlist.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Tue Feb 25 20:59:52 2014 (+0800)
 * Version: 
 * Last-Updated: Tue Feb 25 21:01:44 2014 (+0800)
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

class Wishlist extends \Phalcon\Mvc\Model
{
    public $id;
    public $user_id;
    public $product_id;
    public $addtime;
    public $modtime;
    
    public function initialize()
    {
        $this->setConnectionService('db');
        $this->hasOne("user_id", "\BullSoft\Sample\Models\User", "id", array("alias" => "user"));
        $this->hasOne("product_id", "\BullSoft\Sample\Models\Product", "id", array("alias" => "product"));
    }

    public function getSource()
    {
        return "wishlist";
    }                        
}


/* Wishlist.php ends here */
<?php
/* Category.php --- 
 * 
 * Filename: Category.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Tue Feb 25 18:18:44 2014 (+0800)
 * Version: 
 * Last-Updated: Tue Feb 25 18:37:30 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 6
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

class Category extends \Phalcon\Mvc\Model
{
    public $id;
    public $name;
    public $ck;
    public $lft;
    public $rgt;
    public $addtime;
    public $modtime;
    
    public function initialize()
    {
        $this->setConnectionService('db');
        $this->hasMany("id", "\BullSoft\Sample\Models\Product", "category_id", array("alias" => "product"));
    }

    public function getSource()
    {
        return "category";
    }                    
}

/* Category.php ends here */
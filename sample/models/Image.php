<?php
/* Image.php --- 
 * 
 * Filename: Image.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Mon Mar 24 22:11:14 2014 (+0800)
 * Version: 
 * Last-Updated: Mon Mar 24 22:57:37 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 13
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

class Image extends \Phalcon\Mvc\Model
{
    public $id;
    public $product_id;
    public $product_from_url;
    public $name;
    public $extname;
    public $url_prefix;
    public $addtime;
    public $modtime;

    public function initialize()
    {
        $this->setConnectionService('db');
        $this->product_id = 0;
        $this->addtime = $this->modtime = date('Y-m-d H:i:s');
        $this->hasOne("product_id", "\BullSoft\Sample\Models\Product", "id", array("alias" => "product"));
    }

    public function getSource()
    {
        return "image";
    }                        
}

/* Image.php ends here */
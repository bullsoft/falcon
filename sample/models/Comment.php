<?php
/* Comment.php --- 
 * 
 * Filename: Comment.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Thu Feb 20 22:04:56 2014 (+0800)
 * Version: 
 * Last-Updated: Fri Feb 21 00:18:31 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 7
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

class Comment extends \Phalcon\Mvc\Model
{
    public $id;
    public $product_id;
    public $content;
    public $reply_to_comment_id;
    public $reply_to_user_id;
    public $user_id;
    public $addtime;
    public $modtime;

    public function initialize()
    {
        $this->setConnectionService('db');
        $this->hasMany("id", "\BullSoft\Sample\Models\Comment", "reply_to_comment_id", array("alias" => "reply")); 
        $this->hasOne("user_id", "\BullSoft\Sample\Models\User", "id", array("alias" => "user"));
	$this->hasOne("product_id", "\BullSoft\Sample\Models\Product", "id", array("alias" => "product"));	
	$this->hasOne("reply_to_user_id", "\BullSoft\Sample\Models\User", "id", array("alias" => "replyto"));
    }

    public function getSource()
    {
        return "comment";
    }                            
}

/* Comment.php ends here */
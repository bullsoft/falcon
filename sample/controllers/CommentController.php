<?php
/* CommentController.php --- 
 * 
 * Filename: CommentController.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Thu Feb 20 22:04:41 2014 (+0800)
 * Version: 
 * Last-Updated: Fri Feb 21 00:18:57 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 41
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

namespace BullSoft\Sample\Controllers;
use BullSoft\Sample\Models\Comment as CommentModel;

class CommentController extends ControllerBase
{
    public function listAction($productId)
    {
        $productId = intval($productId);
        $comments = CommentModel::find(array(
            "product_id={$productId} AND reply_to_comment_id=0",
            'order' => "addtime DESC",
            'limit' => 10,
        ));
        /* foreach($comments as $comment) { */
        /*     foreach($comment->reply as $reply) { */
        /*         var_dump($reply->user); */
        /*     } */
        /* } */
        /* exit; */
        $this->view->setVar('comments', $comments);
    }

    public function createAction()
    {
        
    }

    public function removeAction()
    {

    }

    
}

/* CommentController.php ends here */
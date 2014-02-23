<?php
/* CommentController.php --- 
 * 
 * Filename: CommentController.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Thu Feb 20 22:04:41 2014 (+0800)
 * Version: master
 * Last-Updated: Sun Feb 23 23:22:35 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 53
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
        $this->view->setVar('comments', $comments);
    }

    public function createAction()
    {
        $comment = array();
        $comment['product_id'] = $this->request->getPost('product_id', 'int');
        $comment['content'] = $content = $this->request->getPost('content');
        $comment['reply_to_comment_id'] = $reply2C = $this->request->getPost('reply_to_comment_id', 'int');
        $comment['reply_to_user_id'] = $this->request->getPost('reply_to_user_id', 'int');
        $comment['user_id']  = $this->user->id;
        $time = date('Y-m-d H:i:s');
        $comment['addtime'] = $time;
        $comment['modtime'] = $time;

        $model = new CommentModel();
        $model->assign($comment);
        if($model->save() == false) {
            // error log here
            $this->flashJson(500, array(),'评论插入失败');
        } else {
            $this->flashJson(200);
        }
        exit;
    }

    public function removeAction()
    {
        
    }

    
}

/* CommentController.php ends here */
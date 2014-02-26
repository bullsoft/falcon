<?php
/* CommentController.php --- 
 * 
 * Filename: CommentController.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Thu Feb 20 22:04:41 2014 (+0800)
 * Version: master
 * Last-Updated: Wed Feb 26 23:02:19 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 57
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
use BullSoft\Sample\Models\Product as ProductModel;

class CommentController extends ControllerBase
{
    public function listAction($productId, $commentId = 0)
    {
        $productId = intval($productId);
        $commentId = intval($commentId);
        $productId = $productId > 0  ? $productId: $this->request->getPost("product_id", "int");
        if($commentId == 0) {
        	$commentId = intval($this->request->getPost("comment_id", "int"));
			
        }

        if($productId < 1 || $commentId < 0) {
            $this->flashJson(500, array(), "非法主求");
            return ;
        }
        $comments = CommentModel::find(array(
            "product_id={$productId} AND reply_to_comment_id={$commentId}",
            'order' => "addtime DESC",
            'limit' => 10,
        ));
        $this->view->setVar('comments', $comments);
    }

    public function createAction()
    {
    	if(!$this->user) {
			$this->flashJson(403);
			return ;
    	}
        $comment = array();
        $comment['product_id'] = intval($this->request->getPost('product_id', 'int'));
		if($comment['product_id'] < 1) {
			$this->flashJson(500, array(), "非法请求");
			return; 
		}
		$productModel = ProductModel::findFirst($comment['product_id']);
		if(empty($productModel)) {
			$this->flashJson(500, array(), "商品不存在");
			return; 
		}
        $comment['content'] = $content = $this->request->getPost('comment');
        $comment['reply_to_comment_id'] = $reply2C = intval($this->request->getPost('comment_id', 'int'));
        $comment['reply_to_user_id'] = intval($this->request->getPost('reply_to_user_id', 'int'));
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
        return;
    }

    public function removeAction()
    {
        
    }

    
}

/* CommentController.php ends here */
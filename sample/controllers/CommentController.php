<?php
/* CommentController.php --- 
 * 
 * Filename: CommentController.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Thu Feb 20 22:04:41 2014 (+0800)
 * Version: master
 * Last-Updated: Fri Feb 28 23:09:51 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 84
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
use BullSoft\Sample\Models\User as UserModel;
use BullSoft\Sample\Models\Product as ProductModel;

class CommentController extends ControllerBase
{
    public function listAction($productId = 0, $commentId = 0)
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

        $content = trim($this->request->getPost('comment'));
        if(mb_strlen($content, "UTF-8") < 10) {
            $this->flashJson(500, array('comment'), "内容长度至少10字");
            return ;
        }
        $comment['content'] = $content;
        
        $comment['reply_to_comment_id'] = intval($this->request->getPost('comment_id', 'int'));
        if($comment['reply_to_comment_id'] < 1) {
			$this->flashJson(500, array(), "非法请求");
			return; 
        }
        $commentModel = CommentModel::findFirst($comment['reply_to_comment_id']);
        if(empty($commentModel)) {
            $this->flashJson(500, array(), "你所评论的主题不存在");
            return;
        }
        
        $comment['reply_to_user_id'] = intval($this->request->getPost('reply_to_user_id', 'int'));
        if($comment['reply_to_user_id'] < 0) {
			$this->flashJson(500, array(), "非法请求");
            return ;
        }
        if($comment['reply_to_user_id'] > 0) {
            $userModel = UserModel::findFirst($comment['reply_to_user_id']);
            if(empty($userModel)) {
                $this->flashJson(500, array(), "你所评论的用户不存在");
                return;
            }
        }
        $comment['user_id']  = $this->user->id;
        $time = date('Y-m-d H:i:s');
        $comment['addtime'] = $time;
        $comment['modtime'] = $time;

        $model = new CommentModel();
        $model->assign($comment);
        if($model->save() == false) {
            $this->flashJson(500, array(), '评论插入失败');
        } else {
            if(isset($userModel)) {
                $comment['reply_to']["user_id"] = $userModel->id;
                $comment['reply_to']["nickname"] = $userModel->nickname;
                $comment['reply_to']["image_url"] = $userModel->photo;
            }
            $this->flashJson(200, $comment);
        }
        return;
    }

    public function removeAction()
    {
        if(!$this->user) {
            $this->flashJson(403);
            return ;
        }
        $commentId = intval($this->request->getPost('comment_id'));

        if($commentId < 1) {
            $this->flashJson(500, array(), "非法请求");
            return ;
        }

        $commentModel = CommentModel::findFirst($commentId);
        if(empty($commentModel)) {
            $this->flashJson(500, array(), "该评论不存");
            return;
        }
        if($this->user->id != $commentModel->user_id) {
            $this->flashJson(500, array(), "您没有权限删除别人的评论");
            return;
        }
        $this->flashJson(200);
        return ;
    }
}

/* CommentController.php ends here */
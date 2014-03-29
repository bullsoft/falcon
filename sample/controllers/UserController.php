<?php
/* UserController.php --- 
 * 
 * Filename: UserController.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Fri Feb 21 11:41:15 2014 (+0800)
 * Version: master
 * Last-Updated: Fri Mar 21 16:59:21 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 85
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
use BullSoft\Sample\Models\Comment  as CommentModel;
use BullSoft\Sample\Models\Wishlist as WishlistModel;
use BullSoft\Sample\Models\Provider as ProviderModel;
use BullSoft\Sample\Models\Order    as OrderModel;

class UserController extends ControllerBase
{
    const BULL_SOCIAL_URL_PREFIX = 'http://openapi.baidu.com/social/oauth/2.0/authorize?';

    public function loginformAction()
    {
        $ak = $this->di->get('config')->bcs->ak;
        $params = array(
            'media_type'    => '',
            'client_id'     => $ak,
            'state'         => '',
            'response_type' => 'code',
            'redirect_uri'  => $this->url->get('social-oauth/callback'),
            'display'       => 'page',
            'client_type'   => 'web',
        );
        $socialSites = array(
            'sinaweibo' => '新浪微博',
            'qqdenglu'  => '腾讯QQ',
            'baidu'     => '百度',
            'qqweibo'   => '腾讯微博',
            'renren'    => '人人网',
        );
        $socialUrls = array();
        foreach($socialSites as $site => $name) {
            $params['media_type'] = $site;
            $socialUrls[$site] = self::BULL_SOCIAL_URL_PREFIX . http_build_query($params);
        }
        $this->view->setVar('social_urls', $socialUrls);
        $this->view->setVar('social_sites', $socialSites);
    }

    public function logoutAction()
    {
        if(!$this->user) {
            $this->flashJson(403);
            return;
        }
        $this->session->destroy();
        $this->response->redirect('')->sendHeaders();
        return ;
    }

    public function loginAction()
    {
        
    }
    
    public function homeAction()
    {
        if(!$this->user) {
            $this->flashJson(403);
            exit;
        }
        $products = \BullSoft\Sample\Models\Product::find('user_id='.$this->user->id);
        $this->view->setVar('products', $products);
    }

    public function wishlistAction()
    {
        if(!$this->user) {
            $this->flashJson(403);
            exit ;
        }
        $wishlist = WishlistModel::find('user_id='.$this->user->id);
        $this->view->setVar('wishlist', $wishlist);
    }

    public function orderlistAction()
    {
        if(!$this->user) {
            $this->flashJson(403);
            exit ;
        }
        $orderlist = OrderModel::find('user_id='.$this->user->id);
        $this->view->setVar('orderlist', $orderlist);
    }

    public function providersAction()
    {
        if(!$this->user) {
            $this->flashJson(403);
            exit ;
        }
        $providers = ProviderModel::find('user_id='.$this->user->id);
        $this->view->setVar('providers', $providers);
        
    }
    
    public function messagesAction()
    {
        if(!$this->user) {
            $this->flashJson(403);
            exit ;
        }
	$comments = CommentModel::find(array('reply_to_user_id='.$this->user->id, 'order' => 'addtime DESC'));
	$this->view->setVar('comments', $comments);
    }
}

/* UserController.php ends here */
<?php
/* UserController.php --- 
 * 
 * Filename: UserController.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Fri Feb 21 11:41:15 2014 (+0800)
 * Version: master
 * Last-Updated: Tue Feb 25 21:15:25 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 47
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
use BullSoft\Sample\Models\Wishlist as WishlistModel;
    
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
            'redirect_uri'  => $this->url->get('sample/social-o-auth/callback'),
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
        $this->session->destroy();
        $this->response->redirect('')->sendHeaders();
        return ;
    }

    public function registerAction()
    {
        
    }

    public function loginAction()
    {
        
    }
}

/* UserController.php ends here */
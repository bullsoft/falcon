<?php
/* GoodsController.php --- 
 * 
 * Filename: GoodsController.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Thu Nov 28 13:34:36 2013 (+0800)
 * Version: master
 * Last-Updated: Thu Feb 13 14:39:57 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 96
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

class GoodsController extends ControllerBase
{
    public function indexAction()
    {
        
    }

    public function createAction()
    {

    }

    public function fetchAction()
    {
        if(!$this->request->isPost()) {
            $this->flash->error("非法请求！");
            exit(1);
        }
        $url = trim($this->request->getPost("url"));
        if(empty($url)) {
            $this->flash->error("URL不能为空！");
            exit(1);
        }
        $host = 'tcp://115.28.175.32';
        $port = 8080;
        $client = new \BullSoft\WebSocket\Client($host, $port, '/');
        $client->connect();
        $goods = $client->send($url);
        if($goods == false) {
            $this->flash->error("商品请求失败！");
            exit(1);
        }
        $this->view->setVar('goods', $goods);
    }
}


/* GoodsController.php ends here */
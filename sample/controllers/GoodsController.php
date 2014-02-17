<?php
/* GoodsController.php --- 
 * 
 * Filename: GoodsController.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Thu Nov 28 13:34:36 2013 (+0800)
 * Version: master
 * Last-Updated: Mon Feb 17 17:32:00 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 115
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
        $hosts = $this->di->get('config')->phantomjs->hosts->toArray();
        shuffle($hosts);
        $host = reset($hosts);
        $post = 'url='.$url;
        $browser = new \Buzz\Browser();
        try {
            $response = $browser->post($host, array(), $post);
        } catch(\Buzz\Exception\ClientException $e) {
            $this->flashJson(500, array(), $e->getMessage());
            exit(1);
        }
        $content = json_decode($response->getContent(), true);
        if($content['status'] == 200) {
            $goods = $content['data'];
            $goods['from_url'] = $url;
            $goods['from']     = "京东商城";
            $this->flashJson(200, $goods);
        } else {
            $this->flashJson(500, array(), "商品请求失败！");
        }
        exit(1);
    }
}


/* GoodsController.php ends here */
<?php
/* GoodsController.php --- 
 * 
 * Filename: GoodsController.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Thu Nov 28 13:34:36 2013 (+0800)
 * Version: 
 * Last-Updated: Thu Nov 28 18:16:55 2013 (+0800)
 *           By: Gu Weigang
 *     Update #: 43
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
        
        $goods = array();

        $id = basename($url, ".html");
        
        $xpath = \BullSoft\Utility::getPageXPath($url);

        // goods name query node
        $nameQuery = '//*[@id="name"]';
        $nameNode = $xpath->query($nameQuery);

        // goods name
        $goods['name'] = trim($nameNode->item(0)->nodeValue);

        // goods price url
        $priceUrl = "http://p.3.cn/prices/get?skuid=J_" . $id . "&type=1";
        $priceJson = file_get_contents($priceUrl);
        $priceArr = reset(json_decode($priceJson, true));

        // goods price
        $goods['price'] = $priceArr['p'];

        // goods images
        $imgQuery = '//*[@id="spec-list"]/div/ul/li/*';
        $imgNode = $xpath->query($imgQuery);

        $goods['img_s'] = array();
        $goods['img_l'] = array();
        
        foreach($imgNode as $key => $imgItem) {
            echo $key;
            $goods['img_s'][$key] = $imgItem->getAttribute('src');
            $goods['img_l'][$key] = str_replace('/n5/', '/n1/', $goods['img_s'][$key]);
        }

        // goods default image
        $goods['img_default'] = reset($goods['img_l']);
        
        $this->view->setVar('goods', $goods);
    }
}


/* GoodsController.php ends here */
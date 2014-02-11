<?php
/* GoodsController.php --- 
 * 
 * Filename: GoodsController.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Thu Nov 28 13:34:36 2013 (+0800)
 * Version: master
 * Last-Updated: Sat Jan 25 16:55:22 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 88
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

        $itemUrl = parse_url($url, PHP_URL_HOST);

        if($itemUrl == 'item.jd.com') {
            $goods = $this->fetchJD($url);
        } else if($itemUrl == 'item.yixun.com') {
            $goods = $this->fetchYiXun($url);
        }

        $host = '113.200.155.82';
        $port = 6969;
        $local = "null";
        $client = new \BullSoft\WebSocket\Client($host, $port, '/');
        $client->connect();
        $goods = $client->send($url);
        if($goods == false) {
            $this->flash->error("商品请求失败！");
            exit(1);
        }
        var_dump($goods);
        exit;
        $this->view->setVar('goods', $goods);
    }

    public function fetchYiXun($url)
    {
        $goods = array();

        $urlPath = parse_url($url, PHP_URL_PATH);
        sscanf($urlPath, "/item-%d.html", $id);
        $xpath = \BullSoft\Utility::getPageXPath($url);
        
        $nameQuery = '/html/body/div[4]/div[2]/div[2]/div/div[1]/h1';
        $nameNode = $xpath->query($nameQuery);

        $goods['name'] = trim($nameNode->item(0)->nodeValue);

        $priceQuery = '/html/body/div[4]/div[2]/div[2]/div/div[2]/dl[2]/dd/span[1]/text()';
        $priceNode = $xpath->query($priceQuery);
        $goods['price'] = $priceNode->item(0)->nodeValue;

        $document = $xpath->document;
        preg_match('/"pic_num":(\d)/', $document->saveHTML(), $matches);
        $picNum = $matches[1];

        $imgQuery = '//*[@id="list_smallpic"]/ul/li/a/*';
        $imgNode = $xpath->query($imgQuery);
        $imgSrc = $imgNode->item(0)->getAttribute('src');
        $goods['img_s'][0] = $imgSrc;
        $goods['img_l'][0] = str_replace('/pic60/', '/mm/', $goods['img_s'][0]);
        
        for($i = 1; $i < $picNum; ++$i) {
            $goods['img_s'][$i] = substr($imgSrc, 0, -4) . sprintf("-%02d", $i) . ".jpg";
            $goods['img_l'][$i] = str_replace('/pic60/', '/mm/', $goods['img_s'][$i]);
        }

        $goods['img_default'] = reset($goods['img_l']);

        return $goods;
    }
    
    protected function fetchJD($url)
    {
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
            $goods['img_s'][$key] = $imgItem->getAttribute('src');
            $goods['img_l'][$key] = str_replace('/n5/', '/n1/', $goods['img_s'][$key]);
        }

        // goods default image
        $goods['img_default'] = reset($goods['img_l']);

        return $goods;
    }
}


/* GoodsController.php ends here */
<?php
/* GoodsController.php --- 
 * 
 * Filename: GoodsController.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Thu Nov 28 13:34:36 2013 (+0800)
 * Version: master
 * Last-Updated: Wed Feb 19 19:31:42 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 168
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

use BullSoft\Sample\Models\Product as ProductModel;
use Wrench\Protocol\Protocol;
use Wrench\Client;
use Wrench\Socket;
use \InvalidArgumentException;

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
        
        $content = json_decode($this->fetchSocket($url), true);
        // $content = json_decode($this->fetchHttp($url), true);

        if(!$content) {
            $this->flashJson(500, "商品请求失败：网络错误！");
            exit(1);
        }
        if($content['status'] == 200) {
            $goods = $content['data'];
            $goods['from_url'] = $url;
            $goods['from']     = "京东商城";
            $this->flashJson(200, $goods);
        } else {
            $this->flashJson(500, array(), "商品请求失败：商品下架或暂不提供销售");
        }
        exit(1);
    }

    public function insertAction()
    {
        $userId = 1;
        $name = $this->request->getPost('name');
        $price = $this->request->getPost('price');
        $description = $this->request->getPost('description');
        $from = $this->request->getPost('from');
        $fromUrl = $this->request->getPost('from_url');
        $lImgs = $this->request->getPost('l_imgs');

        $model = new ProductModel();
        $model->name = strval($name);
        $model->image_url = reset($lImgs);
        $model->more_image_urls = json_encode($lImgs);
        $model->description = strval($description);
        $model->price = floatval($price);
        $model->from = strval($from);
        $model->from_url = strval($fromUrl);
        $model->user_id = $userId;
        $model->like = 1;
        $model->addtime = $model->modtime = date("Y-m-d H:i:s");
        if($model->save() == false) {
            $this->flashJson(500, array(), "暂时不能推荐商品！");
            foreach ($model->getMessages() as $message) {
                getDI()->get('logger')->error($message->__toString());
            }
        } else {
            $this->flashJson(200, array('forward' => $this->url('sample/index/detail/').$model->id), "商品推荐成功！");
        }
        exit();
    }

    public function fetchHttp($url)
    {
        $hosts = $this->di->get('config')->phantomjs->hosts->toArray();
        shuffle($hosts);
        $host = reset($hosts);
        $post = 'url='.$url;
        $browser = new \Buzz\Browser();
        try {
            $response = $browser->post($host, array(), $post);
        } catch(\Buzz\Exception\ClientException $e) {
            $this->flashJson(500, array(), $e->getMessage());
            return false;
        }
        return $response->getContent();
    }
    
    public function fetchSocket($url)
    {
        $hosts = $this->di->get('config')->websocketd->hosts->toArray();
        shuffle($hosts);
        $host = reset($hosts);        
        $instance = new Client($host, 'http://www.shopbigbang.com/');
        $success = $instance->connect();
        $bytes = $instance->sendData($url, Protocol::TYPE_TEXT);
        $responses = $instance->receive();
        $instance->disconnect();
        return (string) reset($responses);
    }
}


/* GoodsController.php ends here */
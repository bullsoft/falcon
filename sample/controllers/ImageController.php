<?php
/* ImageController.php --- 
 * 
 * Filename: ImageController.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Thu Feb 13 15:21:41 2014 (+0800)
 * Version: 
 * Last-Updated: Thu Feb 13 16:45:58 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 29
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

use Imagine\Image\Box;
use Imagine\Image\Point;

class ImageController extends ControllerBase
{
    public function uploadeAction()
    {
        $conf = $this->getDI()->get('config');
        $bcs = new \BaiduBCS($conf->bcs->ak, $conf->bcs->sk, $conf->bcs->host);
        $objName = "/testtest.jpg";
        $fileUpload = "/home/work/rBEQWFFWsI0IAAAAAAGoimJdp1QAADLRAGHk_cAAaii718.jpg";
        $response = $bcs->create_object($conf->bcs->bucket, $objName, $fileUpload);

        if (! $response->isOK ()) {
            $this->flash->error("抱兼，文件上传失败，请重试！");
        } else {
            $this->flash->success("恭喜，文件上传成功！");
            var_dump($response);
        }
        exit;
    }

    public function getAction($objName)
    {
        $conf = $this->getDI()->get('config');
        $bcs = new \BaiduBCS($conf->bcs->ak, $conf->bcs->sk, $conf->bcs->host);
        $objName = "/testtest.jpg";

        $response = $bcs->get_object($conf->bcs->bucket, $objName);

        if (! $response->isOK ()) {
            $this->flash->error("抱兼，文件获取失败，请重试！");
        } else {
            // $this->flash->success("恭喜，文件获取成功！");
            header("Content-type: image/jpeg");
            echo $response->body;
        }
        exit;
    }

    public function resizeAction()
    {
        $imagine = new \Imagine\Gd\Imagine();
        $image   = $imagine->open("http://bcs.duapp.com/bigbang-product-pic-1/testtest.jpg?sign=".
                                  "MBO:QkAPgTkquNrTWqcbEMOOvrq7:PJxNR7t5fN/xgrHP46Ysu%2BefQI4%3D&response-cache-control=private");
        $size    = new \Imagine\Image\Box(60, 60);
        $mode    = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
        $image->thumbnail($size, $mode)
              ->show("png");
    }
}

/* ImageController.php ends here */
<?php
namespace BullSoft\Sample\Controllers;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        // echo "Hello,I'm here: BullSoft\Sample\IndexController" . PHP_EOL;
        // exit;
    }

    public function detailAction()
    {
        $image = new \BullSoft\Thumb();
        $url = "http://www.qianxs.com/mrMoney/images_n2/ICBC.png";
        $image->readfile($url, "image/jpeg,image/png,image/gif");
        $image->tothumbHD(78, 78, "scale");
        header('Content-Type: image/png');
        echo $image->outputHD(90);
        exit;
    }
}

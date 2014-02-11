<?php
namespace BullSoft\Sample\Controllers;

use BullSoft\Sample\Models\Product as ProductModel;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $products = ProductModel::find();
        $this->view->setVar("products", $products);
    }

    public function detailAction($productId)
    {
        $productId = intval($productId);
        if($productId <= 0) {
            $this->flash->error("产品ID必须大于0！");
            exit(1);
        }

        $product = ProductModel::findFirst('id='.$productId);
        if(empty($product)) {
            $this->flash->error("抱歉，您请求的产品不存在！");
            exit(1);
        }
        $this->view->setVar("product", $product);
    }
}

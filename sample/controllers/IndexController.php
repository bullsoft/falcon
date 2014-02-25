<?php
namespace BullSoft\Sample\Controllers;

use BullSoft\Sample\Models\Product as ProductModel;
use BullSoft\Sample\Models\Category as CategoryModel;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $products = ProductModel::find();
        $categories = CategoryModel::find();

        $this->view->setVar("categories", $categories);
        $this->view->setVar("products", $products);
    }
}

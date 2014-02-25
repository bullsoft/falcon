<?php
namespace BullSoft\Sample\Controllers;

use BullSoft\Sample\Models\Product as ProductModel;
use BullSoft\Sample\Models\Category as CategoryModel;
use BullSoft\Sample\Models\Wishlist as WishlistModel;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $products   = ProductModel::find();
        $categories = CategoryModel::find();
		if(empty($products)) {
	        $this->view->setVar("categories", $categories);
        	$this->view->setVar("products", $products);
			return ;
		}
		$productIds = array();
		$wishlist = array();
		if($this->user) {
			foreach($products as $product) {
				$productIds[] = $product->id;
			}
			$wishes = WishlistModel::find(
				"product_id IN (" . join(",", $productIds) .") AND user_id =".$this->user->id
			);
			foreach ($wishes as $wish) {
				$wishlist[$wish->product_id] = "";
			}

		}

		$this->view->setVar("wishlist", $wishlist);
        $this->view->setVar("categories", $categories);
        $this->view->setVar("products", $products);
    }
}

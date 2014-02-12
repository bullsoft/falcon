<?php
/* CartController.php --- 
 * 
 * Filename: CartController.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Tue Feb 11 19:54:20 2014 (+0800)
 * Version: 
 * Last-Updated: Wed Feb 12 17:17:40 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 49
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

use BullSoft\Cart;
use BullSoft\Sample\Models\Product as ProductModel;
use BullSoft\Sample\Models\Provider as ProviderModel;

class CartController extends ControllerBase
{
    public function indexAction()
    {
        $cart = array();
        if ($this->session->has("shop-cart")) {
            $cart = new Cart\Cart();
            $cart->importJson($this->session->get("shop-cart"));
            $this->view->setVar('cart', $cart);
        } else {
            $this->flash->error('抱歉，您的购物车为空！');
            exit(1);
        }
    }
    
    public function insertItemAction($productId, $providerId)
    {
        $productId = intval($productId);
        $providerId = intval($providerId);
        $product = ProductModel::findFirst($productId);
        $provider = ProviderModel::findFirst("user_id={$providerId} AND product_id={$productId}");
        if(empty($product)) {
            $this->flash->error('抱歉，该商品不存在');
            exit(1);
        }
        
        $item = new Cart\Item();
        
        $item->setId($productId)
             ->setProvider($providerId)
             ->setName($product->name)
             ->setSku('')
             ->setQty(1)
             ->setPrice($provider->price)
             ->setIsTaxable(true)
             ->setIsDiscountable(true);

        $cart = new Cart\Cart();

        if($this->session->has('shop-cart')) {
            $cart->importJson($this->session->get("shop-cart"));
        }
        
        $cart->setItem($item);
        $this->session->set('shop-cart', $cart->toJson());
        
        if($this->request->isAjax()) {
            exit;
        } else {
            $this->forward('sample/cart/index');
            return;
        }
    }

    public function removeAction($productId, $providerId)
    {

    }
    
    public function insert()
    {

    }

    public function update()
    {
        
    }
    

}

/* CartController.php ends here */
<?php
/* CartController.php --- 
 * 
 * Filename: CartController.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Tue Feb 11 19:54:20 2014 (+0800)
 * Version: master
 * Last-Updated: Wed Feb 19 17:21:15 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 81
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
    const BULL_CART_KEY = "shop-cart";
    public function indexAction()
    {
        $sessionCart = array();
        $displayCart = array();
        
        if ($this->session->has(self::BULL_CART_KEY)) {
            $sessionCart = json_decode($this->session->get(self::BULL_CART_KEY), true);
            $totals = array();
            foreach($sessionCart as $providerId => $cartArray) {
                $cart = new Cart\Cart();
                $cart->importJson(json_encode($cartArray));
                $displayCart[$providerId] = $cart;
                $_total = $cart->getTotals();
                $totals[$providerId] = $_total['items'];
            }
            // var_dump($displayCart);
            // exit;
            $this->view->setVar('carts', $displayCart);
            $this->view->setVar('totals', $totals);
        } else {
            $this->flash->error('抱歉，您的购物车为空！');
            exit(1);
        }
    }

    public function orderAction()
    {
        $sessionCart = array();
        $displayCart = array();
        
        if ($this->session->has("shop-cart")) {
            $sessionCart = json_decode($this->session->get("shop-cart"), true);
            $totals = array();
            foreach($sessionCart as $providerId => $cartArray) {
                $cart = new Cart\Cart();
                $cart->importJson(json_encode($cartArray));
                $displayCart[$providerId] = $cart;
                
                $_total = $cart->getTotals();
                $totals[$providerId] = $_total['items'];
            }
            $this->view->setVar('carts', $displayCart);
            $this->view->setVar('totals', $totals);
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
             ->setCustom("image_url", $product->image_url)
             ->setSku('')
             ->setQty(rand(1, 5))
             ->setPrice($provider->price)
             ->setIsTaxable(true)
             ->setIsDiscountable(true);

        $cart = new Cart\Cart();
        $sessionCart = array();
        
        if($this->session->has('shop-cart')) {
            $sessionCart = json_decode($this->session->get("shop-cart"),  true);
            if(isset($sessionCart[$providerId])) {
                $cart->importJson(json_encode($sessionCart[$providerId]));
            }
            // $cart->importJson($this->session->get("shop-cart"));
        }
        
        if($cart->hasItem($item)) {
            $cart->unsetItem($item);
        }
        $cart->setItem($item);                
        
        $sessionCart[$providerId] = $cart->toArray();
        $this->session->set('shop-cart', json_encode($sessionCart));
        
        if($this->request->isAjax()) {
            exit;
        } else {
            $this->response->redirect('sample/cart/index')->sendHeaders();
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
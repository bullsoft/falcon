<?php
/* CartController.php --- 
 * 
 * Filename: CartController.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Tue Feb 11 19:54:20 2014 (+0800)
 * Version: master
 * Last-Updated: Wed Feb 26 22:00:17 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 128
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
            $this->view->setVar('carts', $displayCart);
            $this->view->setVar('totals', $totals);
        } else {
            $this->view->setVar('msg', '抱歉，您的购物车为空！');
        }
    }

    public function orderAction()
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
                
                $_totals = $cart->getTotals();
                $totals[$providerId] = $_totals['items'];
            }
            $this->view->setVar('carts', $displayCart);
            $this->view->setVar('totals', $totals);
        } else {
            $this->view->setVar('msg', '抱歉，您的购物车为空！');
        }        
    }

    public function removeItemAction($productId = 0, $providerId = 0)
    {
        $productId  = intval($productId);
        $providerId = intval($providerId);
        
    	$productId  = $productId  > 0 ? $productId  : $this->request->getPost("product_id",  "int");
        $providerId = $providerId > 0 ? $providerId : $this->request->getPost("provider_id", "int");

        if($productId < 1 || $providerId < 1) {
            $this->flashJson(500, array(), "非法请求");
            return ;
        }

        $cart = new Cart\Cart();
        $sessionCart = array();
        
        if($this->session->has('shop-cart')) {
            $sessionCart = json_decode($this->session->get("shop-cart"),  true);
            if(isset($sessionCart[$providerId])) {
                $cart->importJson(json_encode($sessionCart[$providerId]));
            } else {
                $this->flashJson(500, array(), "购物车中不存在该商品");
                return ;
            }
        }
        
        if($cart->hasItem($productId, false)) {
            $cart->unsetItem($productId, false);
        } else {
            $this->flashJson(500, array(), "购物车中不存在该商品");
            return ;
        }
        if(count($cart->getItemsAsArray()) == 0) {
            $this->session->remove(self::BULL_CART_KEY);
        } else {
            $sessionCart[$providerId] = $cart->toArray();
            $this->session->set(self::BULL_CART_KEY, json_encode($sessionCart));
        }
        $this->flashJson(200);
        return ;
    }
    
    public function insertItemAction($productId = 0, $providerId = 0)
    {
        $productId  = intval($productId);
        $providerId = intval($providerId);
        
    	$productId  = $productId  > 0 ? $productId  : $this->request->getPost("product_id",  "int");
        $providerId = $providerId > 0 ? $providerId : $this->request->getPost("provider_id", "int");

        if($productId < 1 || $providerId < 1) {
            $this->flashJson(500, array(), "非法请求");
            return ;
        }
        
		$qty = $this->request->getPost("qty", "int");
		if($qty < 1) $qty = 1;
		
        $product  = ProductModel::findFirst($productId);

        if(empty($product)) {
            $this->flash->error('抱歉，该商品不存在');
            return ;
        }
        
        $provider = ProviderModel::findFirst("user_id={$providerId} AND product_id={$productId}");

        if(empty($provider)) {
            $this->flash->error('非法请求');
            return ;
        }

        $cart = new Cart\Cart();
        $sessionCart = array();
        
        if($this->session->has('shop-cart')) {
            $sessionCart = json_decode($this->session->get("shop-cart"),  true);
            if(isset($sessionCart[$providerId])) {
                $cart->importJson(json_encode($sessionCart[$providerId]));
            }
        }
        
        if($cart->hasItem($productId, false)) {
            $item = $cart->getItem($productId, false);
            $item->setQty($qty);
            // $cart->unsetItem($item);
        } else {
            $item = new Cart\Item();
            $item->setId($productId)
                 ->setProvider($providerId)
                 ->setQty($qty)
                 ->setName($product->name)
                 ->setCustom("image_url", $product->image_url)
                 ->setSku('')
                 ->setPrice($provider->price)
                 ->setIsTaxable(true)
                 ->setIsDiscountable(true);
            $cart->setItem($item);                
        }
        
        $sessionCart[$providerId] = $cart->toArray();
        $this->session->set('shop-cart', json_encode($sessionCart));
        
        if($this->request->isAjax()) {
        	$ajaxRet = $item->toArray();
			$ajaxRet['totals'] = $cart->getTotals();
            $this->flashJson(200, $ajaxRet);
			return ;
        } else {
            $this->response->redirect('cart/')->sendHeaders();
            return;
        }
    }

    public function removeAllAction()
    {
        $this->session->remove(self::BULL_CART_KEY);
        $this->flashJson(200);
        return ;
    }
}

/* CartController.php ends here */
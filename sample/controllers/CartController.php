<?php
/* CartController.php --- 
 * 
 * Filename: CartController.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Tue Feb 11 19:54:20 2014 (+0800)
 * Version: master
 * Last-Updated: Wed Mar 19 23:35:05 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 234
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
use BullSoft\Sample\Models\Shipment as ShipmentModel;
use BullSoft\Sample\Models\UserShipment as UserShipmentModel;

class CartController extends ControllerBase
{
    const BULL_CART_KEY = "shop-cart";

    public static function getCart()
    {
        $sessionCart = array();
        if (getDI()->get('session')->has(self::BULL_CART_KEY)) {
            $sessionCart = json_decode(getDI()->get('session')->get(self::BULL_CART_KEY), true);
        }
        return $sessionCart;
    }

    public static function destroyCart()
    {
        getDI()->get('session')->remove(self::BULL_CART_KEY);
    }
    
    
    public function indexAction()
    {
        $sessionCart = array();
        $displayCart = array();
        
        if ($this->session->has(self::BULL_CART_KEY)) {
            $sessionCart = json_decode($this->session->get(self::BULL_CART_KEY), true);
            $totals_goods = array();
            $totals_shipments = array();
            foreach($sessionCart as $providerId => $cartArray) {
                $cart = new Cart\Cart();
                $cart->importJson(json_encode($cartArray));
                $displayCart[$providerId] = $cart;
                $_total = $cart->getTotals();
                $totals_goods[$providerId] = $_total['items'];
                $totals_shipments[$providerId] = $_total['shipments'];
            }
            $this->view->setVar('carts', $displayCart);
            $this->view->setVar('totals_goods', $totals_goods);
            $this->view->setVar('totals_shipments', $totals_shipments);
            $this->view->setVar('msg', null);
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
		
		$retArray = array('product_id' => $productId, 'provider_id' => $providerId);
		
        $cart = new Cart\Cart();
        $sessionCart = array();
        
        if($this->session->has('shop-cart')) {
            $sessionCart = json_decode($this->session->get("shop-cart"),  true);
            if(isset($sessionCart[$providerId])) {
                $cart->importJson(json_encode($sessionCart[$providerId]));
            } else {
                $this->flashJson(500, $retArray, "购物车中不存在该商品1");
                return ;
            }
        }
        
        if($cart->hasItem($productId, false)) {
            $cart->unsetItem($productId, false);
        } else {
            $this->flashJson(500, $retArray, "购物车中不存在该商品2");
            return ;
        }
        if(count($cart->getItemsAsArray()) == 0) {
            unset($sessionCart[$providerId]); 
        } else {
            $sessionCart[$providerId] = $cart->toArray();
        }
		if(empty($sessionCart)) {
        	$this->session->remove(self::BULL_CART_KEY);	
		} else {
			$this->session->set(self::BULL_CART_KEY, json_encode($sessionCart));
		}
		$total_num = 0;
		$_totals_goods    = array();
        $_totals_shipment = array();
		foreach ($sessionCart as $_providerId => $_cartArray) {
			$total_num += count($_cartArray['items']);
            $_total = $cart->getTotals();
            $_totals_goods[$_providerId] = $_total['items'];
            $_totals_shipment[$_providerId] = $_total['shipments'];
		}
		
		$retArray['total_num'] = $total_num;
		$retArray['goods_price'] = array_sum($_totals_goods);
		$retArray['total_price'] = array_sum($_totals_shipment) + array_sum($_totals_goods);
		
        $this->flashJson(200, $retArray);
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

    public function shipmentAction($providerId = 0, $shipmentId =0, $price = 0.0)
    {
        $providerId = intval($providerId);
        $shipmentId = intval($shipmentId);
        $shipmentPrice = floatval($price);
        
        $providerId = $providerId > 0 ? $providerId : $this->request->getPost("provider_id", "int");
        $shipmentId = $shipmentId > 0 ? $shipmentId : $this->request->getPost("shipment_id", "int");
        
        if($providerId < 1 || $shipmentId < 1) {
            $this->flashJson(500, array(), "非法请求");
            return ;
        }        
        
        $cart = new Cart\Cart();
        $sessionCart = array();
        
        if($this->session->has('shop-cart')) {
            $sessionCart = json_decode($this->session->get("shop-cart"),  true);
            if(isset($sessionCart[$providerId])) {
                $cart->importJson(json_encode($sessionCart[$providerId]));
            }
        } else {
            $this->flashJson(500, array(), "非法请求");
            return ;
        }

        $productIds = array();
        foreach($cart->getItemsAsArray() as $item) {
            $productIds[] = $item['id'];
        }

        if(empty($productIds)) {
            $this->flashJson(500, array(), '非法请求');
            return ;
        }   
        $providers = ProviderModel::find("user_id={$providerId} AND product_id IN (" . join(", ", $productIds) .")");
        
        $userShipment = UserShipmentModel::findFirst("user_id = " . $providerId . " AND shipment_id = " . $shipmentId);
        if(!empty($userShipment)) {
            $seq = $userShipment->seq;
            $shipmentDetail = array(
                'id'     => $userShipment->shipment->id,
                'vendor' => $userShipment->shipment->slug,
                'method' => $userShipment->shipment->method,
                'price'  => array_sum(\BullSoft\Utility::array_column($providers->toArray(), "shipment_price_".$seq, "id")),
            );
            $cartShipment = new Cart\Shipment();
            $cartShipment->importJson(json_encode($shipmentDetail));
            $cart->setShipment($cartShipment);

            $sessionCart[$providerId] = $cart->toArray();
            $this->session->set('shop-cart', json_encode($sessionCart));            
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
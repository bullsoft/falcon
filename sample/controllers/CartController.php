<?php
/* CartController.php --- 
 * 
 * Filename: CartController.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Tue Feb 11 19:54:20 2014 (+0800)
 * Version: master
 * Last-Updated: Fri Mar 21 15:30:03 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 343
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

class CartController extends ControllerBase
{
    const BULL_CART_KEY = "shop-cart";

    public static function getCart()
    {
        $sessionCart = array();
        $displayCart = array();
        if (getDI()->get('session')->has(self::BULL_CART_KEY)) {
            $sessionCart = json_decode(getDI()->get('session')->get(self::BULL_CART_KEY), true);
            foreach($sessionCart as $providerId => $cartArray) {
                $cart = new Cart\Cart();
                $cart->importJson(json_encode($cartArray));
                $displayCart[$providerId] = $cart;
            }
        }
        return $displayCart;
    }

    public static function toJson($displayCart = array())
    {
        if(empty($displayCart)) {
            $displayCart = self::getCart();
        }
        $sessionCart = array();
        foreach($displayCart as $providerId => $cart) {
            $sessionCart[$providerId] = $cart->toArray();
        }
        return json_encode($sessionCart);
    }
    
    public static function putCart($displayCart)
    {
        getDI()->get('session')->set(self::BULL_CART_KEY, self::toJson($displayCart));
    }
    
    public static function destroyCart()
    {
        getDI()->get('session')->remove(self::BULL_CART_KEY);
    }

    public static function getCartDetail()
    {
        $displayCart = array();

        $displayCart = self::getCart();
        if(empty($displayCart)) {
            return null;
        }

        $totalNum = 0;
        $totalGoods = array();
        $totalShipments = array();
        
        foreach($displayCart as $providerId => $cart) {
            $totalNum += count($cart->getItemsAsArray());
            $totalPrice = $cart->getTotals();
            $totalGoods[$providerId] = $totalPrice['items'];
            $totalShipments[$providerId] = $totalPrice['shipments'];
        }

        return array(
            'carts' => $displayCart,
            'totals_num' => $totalNum,
            'totals_goods' => $totalGoods,
            'totals_shipments' => $totalShipments,
        );
    }
    
    public function indexAction()
    {
        $retVal = self::getCartDetail();
        if(is_null($retVal)) {
            $this->view->setVar('msg', '抱歉，您的购物车为空！');
            return ;
        }
        foreach($retVal as $key => $val) {
            $this->view->setVar($key, $val);
        }
        $this->view->setVar('msg', null);
    }

    public function removeItemAction($productId = 0, $providerId = 0)
    {
        $productId  = intval($productId);
        $providerId = intval($providerId);
        
    	$productId  = $productId  > 0 ? $productId  : $this->request->getPost("product_id",  "int");
        $providerId = $providerId > 0 ? $providerId : $this->request->getPost("provider_id", "int");

        if($productId < 1 || $providerId < 1) {
            $this->flashJson(500, array(), "非法请求");
            exit ;
        }
		$retArray = array('product_id' => $productId, 'provider_id' => $providerId);
		
        $retVal = self::getCartDetail();

        if(is_null($retVal)) {
            $this->flashJson(500, array(), '抱歉，您的购物车为空！');
            exit ;
        }
        
        if(isset($retVal['carts'][$providerId])) {
            $cart = $retVal['carts'][$providerId];
        } else {
            $this->flashJson(500, $retArray, "购物车中不存在该商品1");
            exit ;
        }
        
        if($cart->hasItem($productId, false)) {
            $cart->unsetItem($productId, false);
        } else {
            $this->flashJson(500, $retArray, "购物车中不存在该商品2");
            exit ;
        }
        
        if(count($cart->getItemsAsArray()) == 0) {
            unset($retVal['carts'][$providerId]); 
        }

		if(empty($retVal['carts'])) {
        	$this->session->remove(self::BULL_CART_KEY);	
		} else {
            self::putCart($retVal['carts']);
		}
        
		$retArray['total_num']   = $retVal['totals_num'];
		$retArray['goods_price'] = array_sum($retVal['totals_goods']);
		$retArray['total_price'] = array_sum($retVal['totals_shipments']) + array_sum($retVal['totals_goods']);
        $this->flashJson(200, $retArray);
        exit ;
    }
    
    public function insertItemAction($productId = 0, $providerId = 0)
    {
        $productId  = intval($productId);
        $providerId = intval($providerId);
        
    	$productId  = $productId  > 0 ? $productId  : $this->request->getPost("product_id",  "int");
        $providerId = $providerId > 0 ? $providerId : $this->request->getPost("provider_id", "int");

        if($productId < 1 || $providerId < 1) {
            $this->flashJson(500, array(), "非法请求");
            exit ;
        }

		$qty = $this->request->getPost("qty", "int");
		if($qty < 1) $qty = 1;
		
        $product  = ProductModel::findFirst($productId);

        if(empty($product)) {
            $this->flashJson(500, array(), '抱歉，该商品不存在');
            exit ;
        }
        
        $provider = ProviderModel::findFirst("user_id={$providerId} AND product_id={$productId}");

        if(empty($provider)) {
            $this->flashJson(500, array(), '非法请求');
            exit ;
        }

        $displayCart = self::getCart();
        
        if(isset($displayCart[$providerId])) {
            $cart = $displayCart[$providerId];
        } else {
            $cart = new Cart\Cart();
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
        
        $displayCart[$providerId] = $cart;

        self::putCart($displayCart);
        
        if($this->request->isAjax()) {
        	$ajaxRet = $item->toArray();
			$ajaxRet['totals'] = $cart->getTotals();
            $this->flashJson(200, $ajaxRet);
			exit ;
        } else {
            $this->response->redirect('cart/')->sendHeaders();
            exit ;
        }
    }

    public function shipmentAction($productId = 0, $providerId = 0, $shipmentSeq = 1)
    {
        $productId   = intval($productId);
        $providerId  = intval($providerId);
        $shipmentSeq = intval($shipmentSeq);

        $productId   = $productId   > 0  ? $productId  : $this->request->getPost("product_id",   "int");        
        $providerId  = $providerId  > 0  ? $providerId : $this->request->getPost("provider_id",  "int");
        $shipmentSeq = $shipmentSeq > 0 ? $shipmentSeq : $this->request->getPost("shipment_seq", "int");
        
        if($productId < 1 || $providerId < 1 || $shipmentSeq < 1 ||  $shipmentSeq > 3) {
            $this->flashJson(500, array(), "非法请求");
            exit ;
        }

        $product  = ProductModel::findFirst($productId);
        if(empty($product)) {
            $this->flashJson(500, array(), '抱歉，该商品不存在');
            exit ;
        }
        
        $displayCart = self::getCart();
        if(empty($displayCart)) {
            $this->flashJson(500, array(), "非法请求");
            exit ;
        }
        if(isset($displayCart[$providerId])) {
            $cart = $displayCart[$providerId];
        } else {
            $this->flashJson(500, array(), "过期请求");
            exit ;
        }

        $provider = ProviderModel::findFirst("user_id={$providerId} AND product_id={$productId}");
        if(empty($provider)) {
            $this->flashJson(500, array(), "非法请求");
            exit ;
        }
        
        $shipmentId = $provider->{"shipment_id_".$shipmentSeq};
        
        if(!empty($provider->{"shipment".$shipmentSeq})) {
            if($cart->hasShipment($providerId.'-'.$providerId, false)) {
                $cart->unsetShipment($productId.'-'.$providerId, false);
            }
            $shipmentDetail = array(
                'id'     => $providerId . '-' . $productId,
                'vendor' => $provider->{"shipment".$shipmentSeq}->slug,
                'method' => $provider->{"shipment".$shipmentSeq}->method,
                'price'  => $provider->{"shipment_price_".$shipmentSeq}
            );
            $cartShipment = new Cart\Shipment();
            $cartShipment->importJson(json_encode($shipmentDetail));
            $cart->setShipment($cartShipment);
            $displayCart[$providerId] = $cart;
            self::putCart($displayCart);
            $this->flashJson(200);
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
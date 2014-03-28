<?php
/* OrderController.php --- 
 * 
 * Filename: OrderController.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Thu Feb 27 21:35:23 2014 (+0800)
 * Version: master
 * Last-Updated: Thu Mar 20 21:21:36 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 65
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
use BullSoft\Sample\Models\Order as OrderModel;;
use BullSoft\Sample\Models\OrderDetail as OrderDetailModel;

class OrderController extends ControllerBase
{
    const STATUS_CANCEL              = -1; // 订单取消 
    const STATUS_DONE                = 0;  // 订单完成
    const STATUS_UNPAY               = 1;  // 订单等待支付
    const STATUS_PAYED               = 2;  // 订单已支付，等待支付确认
    const STATUS_UNSHIPPED           = 3;  // 订单支付确认后，等待发货
    const STATUS_SHOPPING            = 4;  // 订单已经发货
    const STATUS_CONFIRM_BY_SHOP     = 5;  // 订单商家确认发货
    const STATUS_CONFIRM_BY_CUSTOMER = 6;  // 订单客户确认收货
    
    public function initialize()
    {
        parent::initialize();
        if(!$this->user) {
            $this->flashJson(403);
            exit;
        }
    }
    
    public function indexAction()
    {
        $retVal = CartController::getCartDetail();
        if(empty($retVal)) {
            $this->view->setVar('msg', '抱歉，您的购物车为空！');
            return ;
        }
        foreach($retVal as $key => $val) {
            $this->view->setVar($key, $val);
        }
        $this->view->setVar('msg', null);            
    }

    public function createAction()
    {
        $retVal = CartController::getCartDetail();
        if(empty($retVal)) {
            $this->flashJson(500);
            exit ;
        }
        
        $carts = $retVal['carts'];

        foreach($carts as $providerId => $cart) {
            $time = time("Y-m-d H:i:s");

            $order = array();
            $order['sn'] = $this->createGuid();
            $order['user_id'] = $this->user->id;
            $order['status']  = self::STATUS_UNPAY;
            $order['price']   = array_sum($retVal['totals_goods']) + array_sum($retVal['totals_shipments']);
            $order['detail']  = $cart->toJson();
            $order['addtime'] = $order['modtime'] = $time;

            $orderModel = new OrderModel();
            $orderModel->assign($order);
            $this->db->begin();
            if($orderModel->save() == false) {
                foreach($orderModel->getMessages() as $message) {
                    echo $message . PHP_EOL;
                }
                $this->db->rollback();
                $this->flashJson(500);
                exit ;                
            }

            foreach($cart->getItemsAsArray() as $item) {
                $orderDetailModel = new OrderDetailModel();
                $orderDetailModel->user_id  = $this->user->id;
                $orderDetailModel->order_id = $orderModel->id;
                $orderDetailModel->product_id   = $item['id'];
                $orderDetailModel->product_name = $item['name'];
                $orderDetailModel->provider_id  = $providerId;
                $orderDetailModel->qty   = $item['qty'];
                $orderDetailModel->price = $item['price'];
                $orderDetailModel->discount = 1.0;
                $orderDetailModel->addtime  = $orderDetailModel->modtime = $time;

                if($orderDetailModel->save() == false) {
                    foreach($orderDetailModel->getMessages() as $message) {
                        echo $message . PHP_EOL;
                    }
                    $this->db->rollback();
                    $this->flashJson(500);
                    exit ;
                }
            }
            $this->db->commit();
        }
        CartController::destroyCart();
        $this->flashJson(200);
    }
    
    public function createGuid($namespace = '')
    {
        $guid = '';
        $uid = uniqid("", true);
        $data = $namespace;
        $data .= $_SERVER['REQUEST_TIME'];
        $data .= $_SERVER['HTTP_USER_AGENT'];
        $data .= $_SERVER['SERVER_ADDR'];
        $data .= $_SERVER['SERVER_PORT'];
        $data .= $_SERVER['REMOTE_ADDR'];
        $data .= $_SERVER['REMOTE_PORT'];
        $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
        $guid =
            substr($hash,  0,  8) . 
            '-' .
            substr($hash,  8,  4) .
            '-' .
            substr($hash, 12,  4) .
            '-' .
            substr($hash, 16,  4) .
            '-' .
            substr($hash, 20, 12);
        return $guid;
    }
}


/* OrderController.php ends here */
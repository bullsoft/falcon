<?php
/* OrderController.php --- 
 * 
 * Filename: OrderController.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Thu Feb 27 21:35:23 2014 (+0800)
 * Version: 
 * Last-Updated: Thu Feb 27 21:44:16 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 8
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

class OrderController extends ControllerBase
{
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
        $sessionCart = array();
        $displayCart = array();
        
        if ($this->session->has(CartController::BULL_CART_KEY)) {
            $sessionCart = json_decode($this->session->get(CartController::BULL_CART_KEY), true);
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
            $this->view->setVar('msg', null);            
        } else {
            $this->view->setVar('msg', '抱歉，您的购物车为空！');
        }        
    }
}


/* OrderController.php ends here */
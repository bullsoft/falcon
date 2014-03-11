<?php 
namespace BullSoft\Sample\Controllers;
use BullSoft\Cart;

class ControllerBase extends \Phalcon\Mvc\Controller
{
    protected $config;
    protected $user;
    
    protected function initialize()
    {
        $this->config = $this->getDI()->get('config');

        $isClose = isset($_GET['close'])?(bool)$_GET['close']:true;
        
        if((bool)$this->config->application->close && $isClose) {
            $this->forward("sample/error/countdown");
            return;
        }

        $this->view->setVar("controller", $this->dispatcher->getControllerName());
        $this->view->setVar("action", $this->dispatcher->getActionName());
        $this->view->setVar("module", $this->dispatcher->getModuleName());
        
        if($this->di->has('user')) {
            $this->user = $this->di->get('user');
        } else {
            $this->user = null;
        }

        $displayCart = array();
        $totals      = array();
        $num         = 0;
        if ($this->session->has(CartController::BULL_CART_KEY)) {
            $sessionCart = json_decode($this->session->get(CartController::BULL_CART_KEY), true);
            $totals = array();
            foreach($sessionCart as $providerId => $cartArray) {
                $cart = new Cart\Cart();
                $cart->importJson(json_encode($cartArray));
                $num += count($cartArray['items']);
                $displayCart[$providerId] = $cart;
                $_total = $cart->getTotals();
                $totals[$providerId] = $_total['items'];
            }
        }
        
        $this->view->setVar('global_carts', $displayCart);
        $this->view->setVar('global_cart_totals', $totals);
        $this->view->setVar('global_cart_num', $num);        
        $this->view->setVar('login_user', $this->user);
    }

    protected function forward($uri)
    {
    	$uriParts = explode('/', $uri);
    	return $this->dispatcher->forward(
    		array(
                'module'     => $uriParts[0],
    			'controller' => $uriParts[1], 
    			'action' => $uriParts[2]
    		)
    	);
    }

    protected function flashJson($status, $data = array(), $msg="")
    {
        $this->response->sendHeaders();
        echo json_encode(array(
            'status' => intval($status),
            'data'   => $data,
            'msg'    => $msg,
        ));
    }
}

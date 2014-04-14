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

        $cartDetail = CartController::getCartDetail();
        if(empty($cartDetail)) {
            $this->view->setVar('global_carts', array());
            $this->view->setVar('global_cart_totals', array());
            $this->view->setVar('global_cart_num', 0);
        } else {
            $this->view->setVar('global_carts', $cartDetail['carts']);
            $this->view->setVar('global_cart_totals', $cartDetail['totals_goods']);
            $this->view->setVar('global_cart_num', $cartDetail['totals_num']);
        }
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
        $this->response->setContentType('application/json');
        $this->response->sendHeaders();
        echo json_encode(array(
            'status' => intval($status),
            'data'   => $data,
            'msg'    => $msg,
        ));
        exit ;
    }
}

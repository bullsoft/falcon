<?php 
namespace BullSoft\Sample\Controllers;

class ControllerBase extends \Phalcon\Mvc\Controller
{
    protected $config;
    protected $user;
    
    protected function initialize()
    {
        $this->config = $this->getDI()->get('config');
        if($this->di->has('user')) {
            $this->user = $this->di->get('user');
        } else {
            $this->user = null;
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
        $this->response->sendHeaders();
        echo json_encode(array(
            'status' => intval($status),
            'data'   => $data,
            'msg'    => $msg,
        ));
    }
}

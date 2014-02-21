<?php
/* Acl.php --- 
 * 
 * Filename: Acl.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Fri Feb 21 16:47:27 2014 (+0800)
 * Version: 
 * Last-Updated: Sat Feb 22 00:07:28 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 46
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

namespace BullSoft\Sample\Plugins;

use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;

class Acl extends Plugin
{
    protected $di;
    protected $eventManager;

    public function __construct($di, $evtManager)
    {
        $this->di = $di;
        $this->eventManager = $evtManager;
    }

    public function beforeDispatch(\Phalcon\Events\Event $event, \Phalcon\Mvc\Dispatcher $dispatcher)
    {
        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();
        $dbUser = null;
        $userId = $this->session->get('identity');
        if(!$userId) {
        } else {
            $dbUser = \BullSoft\Sample\Models\User::findFirst(intval($userId));
            $this->di->set('user', $dbUser);
        }
        return true;
    }
}

/* Acl.php ends here */
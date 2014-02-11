<?php
/* User.php --- 
 * 
 * Filename: User.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Mon Feb 10 16:29:55 2014 (+0800)
 * Version: 
 * Last-Updated: Mon Feb 10 16:34:51 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 5
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

namespace BullSoft\Sample\Models;

class User extends \Phalcon\Mvc\Model
{
    public $id;
    public $username;
    public $password;
    public $nickname;
    public $photo;
    public $email;
    public $level;
    public $is_active;
    public $active_code;
    public $addtime;
    public $acttime;
    public $modtime;

    public function initialize()
    {
        $this->setConnectionService('db');
    }

    public function getSource()
    {
        return "user";
    }                        
}


/* User.php ends here */
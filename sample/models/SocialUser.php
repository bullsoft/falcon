<?php
/* SocailUser.php --- 
 * 
 * Filename: SocailUser.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Fri Feb 21 15:10:03 2014 (+0800)
 * Version: 
 * Last-Updated: Fri Feb 21 23:17:13 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 9
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

class SocialUser extends \Phalcon\Mvc\Model
{
    public $id;
    public $user_id = 0;
    public $social_id;
    public $media_type;
    public $media_uid;
    public $username;
    public $sex;
    public $birthday;
    public $tinyurl;
    public $headurl;
    public $mainurl;
    public $hometown_location;
    public $work_history;
    public $university_history;
    public $hs_history;
    public $province;
    public $city;
    public $is_verified;

    public function initialize()
    {
        $this->setConnectionService('db');
        $this->hasOne('user_id', "\BullSoft\Sample\Models\User", "id", array('alias' => 'user'));
    }

    public function getSource()
    {
        return "social_user";
    }                 
}

/* SocailUser.php ends here */
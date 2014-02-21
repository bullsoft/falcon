<?php
/* SocialOAuth.php --- 
 * 
 * Filename: SocialOAuth.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Wed Feb 19 17:57:32 2014 (+0800)
 * Version: master
 * Last-Updated: Sat Feb 22 01:21:05 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 143
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
use BullSoft\Sample\Models\User as UserModel;
use BullSoft\Sample\Models\SocialUser as SocialUserModel;

class SocialOAuthController extends ControllerBase
{
    const BULL_SOCIAL_COOKIE_KEY = "social_oauth";

    protected function getCurlClient()
    {
        $client = new \Buzz\Client\Curl();
        $client->setTimeout(5);
        $client->setVerifyPeer(false);
        $client->setMaxRedirects(0);
        $client->setOption(\CURLOPT_CONNECTTIMEOUT, 3);
        $client->setOption(\CURLOPT_USERAGENT, "baidu-apiclient-php-2.0");
        $client->setOption(\CURLOPT_HTTP_VERSION, \CURL_HTTP_VERSION_1_1);
        $client->setOption(\CURLOPT_POST, false);
        return $client;
    }

    public function getSocialCookie()
    {
        if(!$this->session->has(self::BULL_SOCIAL_COOKIE_KEY)) {
            return false;
        }
        $socialCookie = $this->session->get(self::BULL_SOCIAL_COOKIE_KEY);
        return json_decode($socialCookie->getValue(), true);        
    }
    
    public function callbackAction()
    {
        $code = $this->request->getQuery('code');
        $request = new \Buzz\Message\Request();
        $request->setHost('https://openapi.baidu.com');
        $params = array(
            "grant_type"    => "authorization_code",
            "client_id"     => $this->getDI()->get('config')->bcs->ak,
            "client_secret" => $this->getDI()->get('config')->bcs->sk,
            "redirect_uri"  => $this->url->get('sample/social-o-auth/callback'),
            "code"          => $code,
        );
        $request->setResource('/social/oauth/2.0/token?'. http_build_query($params));
        $response = new \Buzz\Message\Response();
        $client = $this->getCurlClient();
        $client->send($request, $response);
        if($response->isOk()) {
            $this->session->set(self::BULL_SOCIAL_COOKIE_KEY, $response->getContent());
            $socialAccount = json_decode($response->getContent(), true);
            $this->userInfoAction();
            $this->response->redirect('sample/index/index')->sendHeaders();
        } else {
            echo "error";
        }
        exit;
    }
    
    public function userInfoAction()
    {
        if($this->session->has(self::BULL_SOCIAL_COOKIE_KEY)) {
            $socialCookie = $this->session->get(self::BULL_SOCIAL_COOKIE_KEY);
            $socialOAuth = json_decode($socialCookie, true);
            $request = new \Buzz\Message\Request();
            $request->setHost("https://openapi.baidu.com");
            $request->setResource("/social/api/2.0/user/info?access_token=".$socialOAuth['access_token']);
            $response = new \Buzz\Message\Response();
            $client = $this->getCurlClient();
            $client->send($request, $response);
            if($response->isOk()) {
                $socialUser = json_decode($response->getContent(), true);
                var_dump($socialUser);
                $socialUserModel = SocialUserModel::findFirst('social_uid='.intval($socialUser['social_uid']));
                $time = date('Y-m-d H:i:s');
                if(empty($socialUserModel)) {
                    $socialUserModel = new SocialUserModel();
                    $socialUserModel->assign($socialUser);
                    if($socialUserModel->save() == false) {
                        // foreach ($socialUserModel->getMessages() as $message) {
                        // echo $message. "<br />";
                        // }
                        return false;
                    }
                }
                if($socialUserModel->user_id > 0) {
                    $this->session->set('identity', $socialUserModel->user_id);
                    return false;
                }
                try {
                    $this->db->begin();
                    $userModel = new UserModel();
                    $userModel->username = 'shopbigbang_'.\BullSoft\Utility::generateRandomString(8);
                    $userModel->nickname = $socialUser['username'];
                    $userModel->password = \BullSoft\Utility::generateRandomString();
                    $userModel->photo    = $socialUser['tinyurl'];
                    $userModel->email    = \BullSoft\Utility::generateRandomString(32)."@";
                    $userModel->level    = 1;
                    $userModel->is_active = 'N';
                    $userModel->active_code = \BullSoft\Utility::generateRandomString(32);
                    $userModel->addtime = $time;
                    $userModel->modtime = $time;
                    if($userModel->save() == false) {
                        foreach ($userModel->getMessages() as $message) {
                            echo $message. "<br />";
                        }
                        $this->db->rollback("不能保存用户！");
                    }
                    $socialUserModel->user_id = $userModel->id;
                    if($socialUserModel->save() == false) {
                        foreach ($socialUserModel->getMessages() as $message) {
                            echo $message. "<br />";
                        }
                        $this->db->rollback("不能保存用户！");
                    }
                    $this->session->set('identity', $userModel->id);
                    $this->db->commit();
                } catch(\Exception $e) {
                    $this->db->rollback();
                }
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
        exit;
    }

    public function bindUserAction()
    {
        
    }

    public function unbindUserAction()
    {

    }

    public function bindStatusAction()
    {

    }

    public function shareOneAction()
    {
        $socialCookie = $this->getSocialCookie();
        if(!$socialCookie) {
            echo "login first";
        }
        $request = new \Buzz\Message\Request();
        $request->setHost('https://openapi.baidu.com');
        $params = array(
            "access_token" => $socialCookie['access_token'],
            "content"      => "bigbang测试分享微博",
            "url"          => "http://www.baidu.com",
        );
        $request->setResource("/social/api/2.0/share?".http_build_query($params));
        $response = new \Buzz\Message\Response();
        $client = $this->getCurlClient();
        $client->send($request, $response);
        if($response->isOk()) {
            var_dump($response->getContent());
        } else{
            echo "error";
        }
        exit;
    }

    public function shareBatchAction()
    {

    }

    public function friendsAction()
    {

    }


    
}
/* SocialOAuth.php ends here */
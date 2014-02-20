<?php
/* SocialOAuth.php --- 
 * 
 * Filename: SocialOAuth.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Wed Feb 19 17:57:32 2014 (+0800)
 * Version: master
 * Last-Updated: Thu Feb 20 20:54:44 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 81
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

    protected function getSocialCookie()
    {
        if(!$this->cookie->has(self::BULL_SOCIAL_COOKIE_KEY)) {
            return false;
        }
        $socialCookie = $this->cookie->get(self::BULL_SOCIAL_COOKIE_KEY);
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
            $this->cookie->set(self::BULL_SOCIAL_COOKIE_KEY, $response->getContent(), time()+15*86400);
            setcookie(self::BULL_SOCIAL_COOKIE_KEY, $response->getContent(), time()+15*86400);
            $socialAccount = json_decode($response->getContent(), true);
            foreach($socialAccount as $key => $val) {
                echo "$key = $val <br />";
            }
            echo "OK";
        } else {
            echo "error";
        }
        exit;
    }
    
    public function userInfoAction()
    {
        if($this->cookie->has(self::BULL_SOCIAL_COOKIE_KEY)) {
            $socialCookie = $this->cookie->get(self::BULL_SOCIAL_COOKIE_KEY);
            $socialOAuth = json_decode($socialCookie->getValue(), true);
            $request = new \Buzz\Message\Request();
            $request->setHost("https://openapi.baidu.com");
            $request->setResource("/social/api/2.0/user/info?access_token=".$socialOAuth['access_token']);
            $response = new \Buzz\Message\Response();
            $client = $this->getCurlClient();
            $client->send($request, $response);
            if($response->isOk()) {
                var_dump($response->getContent());
            } else {
                echo "connection error";
            }
        } else {
            echo "login social first";
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
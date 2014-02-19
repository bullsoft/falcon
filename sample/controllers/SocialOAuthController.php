<?php
/* SocialOAuth.php --- 
 * 
 * Filename: SocialOAuth.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Wed Feb 19 17:57:32 2014 (+0800)
 * Version: master
 * Last-Updated: Wed Feb 19 21:24:32 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 39
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
        $client = new \Buzz\Client\Curl();
        $client->setTimeout(5);
        // $client->setVerifyPeer($request->isSecure());
        $client->setMaxRedirects(0);
        $client->setOption(\CURLOPT_CONNECTTIMEOUT, 3);
        $client->setOption(\CURLOPT_USERAGENT, "baidu-apiclient-php-2.0");
        $client->setOption(\CURLOPT_HTTP_VERSION, \CURL_HTTP_VERSION_1_1);
        $client->setOption(\CURLOPT_POST, false);
        $client->send($request, $response);
        if($response->isOk()) {
            var_dump(json_decode($response->getContent(), true));
        } else {
            var_dump("error");
        }
    }
}
/* SocialOAuth.php ends here */
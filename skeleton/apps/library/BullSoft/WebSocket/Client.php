<?php
/* Client.php --- 
 * 
 * Filename: Client.php
 * Description: 
 * Author: Gu Weigang  * Maintainer: 
 * Created: Sat Jan 25 00:09:00 2014 (+0800)
 * Version: 
 * Last-Updated: Sat Jan 25 10:14:21 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 76
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

namespace WebSocket;

class Client
{
    private $host;
    private $port;
    private $path;
    private $sockfd = null;
    private $isConnected = false;
    
    public function __construct($host, $port, $path='/')
    {
        $this->host = $host;
        $this->port = $port;
        $this->path = $path;
    }

    public function connect()
    {
        $this->sockfd = fsockopen($this->host, $this->port, $errno, $errstr, 2);

        // generate random string
        $key = base64_encode($this->generateRandomString(16, false, true));

        // websocket http head
        $head = "GET " . $this->path . " HTTP/1.1\r\n".
            "Upgrade: WebSocket\r\n".
            "Connection: Upgrade\r\n".
            "Origin: null\r\n".
            "Host: " . $this->host . "\r\n".
            "Sec-WebSocket-Key: " . $key . "\r\n".
            "Sec-WebSocket-Version: 13\r\n\r\n" ;

        fwrite($this->sockfd, $head) or die('error:'.$errno.':'.$errstr);

        // get response from websocket server
        $headers = fread($this->sockfd, 8192);

        // get accept key
		preg_match('#Sec-WebSocket-Accept:\s(.*)$#mU', $headers, $matches);
        
        // get key accept
        if(empty($matches)) {
            $keyAccept = "";
        } else {
            $keyAccept = trim($matches[1]);
        }
		$expectedResonse = base64_encode(pack('H*', sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
		$this->isConnected = ($keyAccept === $expectedResonse) ? true : false;
        return $this->isConnected;
    }

    public function reconnect()
    {
        $this->isConnected = false;
        fclose($this->sockfd);
        $this->connect();
    }
    
    public function send($data)
    {
        if($this->isConnected) {
            fwrite($this->sockfd, $this->hybi10Encode($data)) or die('error:'.$errno.':'.$errstr);
            $wsdata = fread($this->sockfd, 8192);
            var_dump(json_decode($this->hybi10Decode($wsdata), true));
        } else {
            return false;
        }
    }
    
    public function __destruct()
    {
        fclose($this->sockfd);
    }
    
    private function hybi10Decode($data)
    {
        $bytes = $data;
        $dataLength = '';
        $mask = '';
        $coded_data = '';
        $decodedData = '';
        $secondByte = sprintf('%08b', ord($bytes[1]));
        $masked = ($secondByte[0] == '1') ? true : false;
        $dataLength = ($masked === true) ? ord($bytes[1]) & 127 : ord($bytes[1]);

        if($masked === true) {
            if($dataLength === 126) {
                $mask = substr($bytes, 4, 4);
                $coded_data = substr($bytes, 8);
            } elseif($dataLength === 127) {
                $mask = substr($bytes, 10, 4);
                $coded_data = substr($bytes, 14);
            } else {
                $mask = substr($bytes, 2, 4);       
                $coded_data = substr($bytes, 6);        
            }
            for($i = 0; $i < strlen($coded_data); $i++) {       
                $decodedData .= $coded_data[$i] ^ $mask[$i % 4];
            }
        } else {
            if($dataLength === 126) {          
                $decodedData = substr($bytes, 4);
            } elseif($dataLength === 127) {           
                $decodedData = substr($bytes, 10);
            } else {               
                $decodedData = substr($bytes, 2);       
            }       
        }   
        return $decodedData;
    }

    private function hybi10Encode($payload, $type = 'text', $masked = true)
    {
        $frameHead = array();
        $frame = '';
        $payloadLength = strlen($payload);

        switch ($type) {
            case 'text':
                // first byte indicates FIN, Text-Frame (10000001):
                $frameHead[0] = 129;
                break;
            case 'close':
                // first byte indicates FIN, Close Frame(10001000):
                $frameHead[0] = 136;
                break;
            case 'ping':
                // first byte indicates FIN, Ping frame (10001001):
                $frameHead[0] = 137;
                break;
            case 'pong':
                // first byte indicates FIN, Pong frame (10001010):
                $frameHead[0] = 138;
                break;
        }

        // set mask and payload length (using 1, 3 or 9 bytes)
        if ($payloadLength > 65535) {
            $payloadLengthBin = str_split(sprintf('%064b', $payloadLength), 8);
            $frameHead[1] = ($masked === true) ? 255 : 127;
            for ($i = 0; $i < 8; $i++) {
                $frameHead[$i + 2] = bindec($payloadLengthBin[$i]);
            }

            // most significant bit MUST be 0 (close connection if frame too big)
            if ($frameHead[2] > 127) {
                $this->close(1004);
                return false;
            }
        } elseif ($payloadLength > 125) {
            $payloadLengthBin = str_split(sprintf('%016b', $payloadLength), 8);
            $frameHead[1] = ($masked === true) ? 254 : 126;
            $frameHead[2] = bindec($payloadLengthBin[0]);
            $frameHead[3] = bindec($payloadLengthBin[1]);
        } else {
            $frameHead[1] = ($masked === true) ? $payloadLength + 128 : $payloadLength;
        }

        // convert frame-head to string:
        foreach (array_keys($frameHead) as $i) {
            $frameHead[$i] = chr($frameHead[$i]);
        }

        if ($masked === true) {
            // generate a random mask:
            $mask = array();
            for ($i = 0; $i < 4; $i++) {
                $mask[$i] = chr(rand(0, 255));
            }

            $frameHead = array_merge($frameHead, $mask);
        }
        $frame = implode('', $frameHead);
        // append payload to frame:
        for ($i = 0; $i < $payloadLength; $i++) {
            $frame .= ($masked === true) ? $payload[$i] ^ $mask[$i % 4] : $payload[$i];
        }

        return $frame;
    }

    private function generateRandomString($length = 10, $addSpaces = true, $addNumbers = true)
    {  
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!"§$%&/()=[]{}';
        $useChars = array();
        // select some random chars:    
        for($i = 0; $i < $length; $i++) {
            $useChars[] = $characters[mt_rand(0, strlen($characters)-1)];
        }
        // add spaces and numbers:
        if($addSpaces === true) {
            array_push($useChars, ' ', ' ', ' ', ' ', ' ', ' ');
        }
    
        if($addNumbers === true) {
            array_push($useChars, rand(0,9), rand(0,9), rand(0,9));
        }
        shuffle($useChars);
        $randomString = trim(implode('', $useChars));
        $randomString = substr($randomString, 0, $length);
        return $randomString;
    }
}

/* $host = '113.200.155.82'; */
/* $port = 6969; */
/* $local = "null"; */
/* $data = "http://item.jd.com/892222.html"; */

/* $client = new Client($host, $port, '/'); */
/* $client->connect(); */
/* $client->send($data); */

/* Client.php ends here */
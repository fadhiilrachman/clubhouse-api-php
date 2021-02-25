<?php

/**
* Clubhouse Private API
* @author Fadhiil Rachman <https://www.instagram.com/fadhiilrachman>
* @version 0.0.2
* @license https://github.com/fadhiilrachman/clubhouse-api-php/blob/master/LICENSE The BSD-3-Clause License
*/

namespace FadhiilRachman\Clubhouse;

use \FadhiilRachman\Clubhouse\Constants;
use \FadhiilRachman\Clubhouse\Sessions;
use \FadhiilRachman\Clubhouse\ClubhouseException;

class Requests extends Sessions
{

    private $curl;
    public $headers=[];
    
    private function setting_curl() {
        $this->curl = curl_init();
        curl_setopt_array($this->curl, array(
            CURLOPT_USERAGENT           => Constants::USER_AGENT,
            CURLOPT_RETURNTRANSFER      => 1,
            CURLOPT_VERBOSE             => 0,
            CURLOPT_SSL_VERIFYHOST      => 0,
            CURLOPT_SSL_VERIFYPEER      => 0
        ));
        if($this->isLoggedIn) {
            if($this->user_id!==null) {
                array_push($this->headers, 'CH-UserID: ' . $this->user_id);
            }
            array_push($this->headers, 'CH-DeviceID: ' . $this->device_id);
            array_push($this->headers, 'Authorization: Token ' . $this->auth_token);
        }
    }

    public function get($endpoint, $param=0) {
        $this->setting_curl();
        curl_setopt_array($this->curl, array(
            CURLOPT_URL             => Constants::API_URL . $endpoint . ( $param ? '?'.http_build_query($param) : ''),
            CURLOPT_HTTPHEADER      => $this->headers
        ));
        $data = curl_exec($this->curl);
        if(!$data) {
            throw new ClubhouseException('cUrl has been crashed', 500);
            return ;
        }
        curl_close($this->curl);
        return json_decode($data, true);
    }

    public function post($endpoint, $post=[]) {
        $this->setting_curl();
        array_push($this->headers, 'Content-Type: application/json; charset=utf-8');
        array_push($this->headers, 'Accept: application/json; charset=utf-8');
        curl_setopt_array($this->curl, array(
            CURLOPT_URL             => Constants::API_URL . $endpoint,
            CURLOPT_HTTPHEADER      => $this->headers,
            CURLOPT_POST            => 1,
            CURLOPT_POSTFIELDS      => json_encode($post, true)
        ));
        $data = curl_exec($this->curl);
        if(!$data) {
            throw new ClubhouseException('cUrl has been crashed', 500);
            return ;
        }
        curl_close($this->curl);
        return json_decode($data, true);
    }

}
<?php

/**
* Clubhouse Private API
* @author Fadhiil Rachman <https://www.instagram.com/fadhiilrachman/>
* @version 1.2.1
* @license https://github.com/fadhiilrachman/clubhouse-api-php/blob/master/LICENSE The BSD-3-Clause License
*/

namespace FadhiilRachman\Clubhouse;

use \FadhiilRachman\Clubhouse\Constants;
use \FadhiilRachman\Clubhouse\Sessions;
use \FadhiilRachman\Clubhouse\ClubhouseException;

class Requests extends Sessions
{

    public $curl;
    public $headers=[];
    
    public function setting_curl($headers) {
        $this->curl = curl_init();
        if(count($headers)>0) {
            foreach ($headers as $key=>$value) {
                $__headers[] = $key . ': ' . $value;
            }
        }
        curl_setopt_array($this->curl, array(
            CURLOPT_USERAGENT         => Constants::USER_AGENT,
            CURLOPT_HTTPHEADER        => $__headers,
            CURLOPT_RETURNTRANSFER    => 1,
            CURLOPT_VERBOSE           => 0,
            CURLOPT_SSL_VERIFYHOST    => 0,
            CURLOPT_SSL_VERIFYPEER    => 0
        ));
    }

    private function validate_token($data) {
        if($data!==null) {
            if( is_array($data) && array_key_exists('is_blocked', $data) ) {
                if($data['is_blocked']==true) throw new ClubhouseException('You\'re blocked.', 500);
            }
            if( is_array($data) && array_key_exists('detail', $data) ) {
                throw new ClubhouseException($data['detail'], 500);
            }
        }
        return $data;
    }

    public function get($endpoint, $param=0) {
        $this->setting_curl($this->headers);
        curl_setopt_array($this->curl, array(
            CURLOPT_URL             => Constants::API_URL . $endpoint . ( $param ? '?'.http_build_query($param) : '')
        ));
        $data = curl_exec($this->curl);
        if(!$data) {
            throw new ClubhouseException('cUrl has been crashed', 500);
        }
        curl_close($this->curl);
        return $this->validate_token(json_decode($data, true));
    }

    public function post($endpoint, $post=[]) {
        $headers = $this->headers;
        $headers['Content-Type'] = 'application/json; charset=utf-8';
        $headers['Accept'] = 'application/json; charset=utf-8';
        $this->setting_curl($headers);
        curl_setopt_array($this->curl, [
            CURLOPT_URL             => Constants::API_URL . $endpoint,
            CURLOPT_POST            => 1,
            CURLOPT_POSTFIELDS      => json_encode($post, true)
        ]);
        $data = curl_exec($this->curl);
        if(!$data) {
            throw new ClubhouseException('cUrl has been crashed', 500);
        }
        curl_close($this->curl);
        return $this->validate_token(json_decode($data, true));
    }

    public function upload($endpoint, $file_path, $file_name='image.jpg', $file_ext='image/jpeg') {
        $this->setting_curl($this->headers);
        curl_setopt_array($this->curl, [
            CURLOPT_URL             => Constants::API_URL . $endpoint,
            CURLOPT_POST            => 1,
            CURLOPT_POSTFIELDS      => [
                'file'  => new \CURLFile($file_path, $file_ext, $file_name)
            ],
        ]);
        $data = curl_exec($this->curl);
        if(!$data) {
            throw new ClubhouseException('cUrl has been crashed', 500);
        }
        curl_close($this->curl);
        return $this->validate_token(json_decode($data, true));
    }

}
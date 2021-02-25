<?php

/**
* Clubhouse Private API
* @author Fadhiil Rachman <https://www.instagram.com/fadhiilrachman>
* @version 0.0.3
* @license https://github.com/fadhiilrachman/clubhouse-api-php/blob/master/LICENSE The BSD-3-Clause License
*/

namespace FadhiilRachman\Clubhouse;

class Sessions
{
    
    public $isLoggedIn=false;
    public $device_id;

    public $phone_number=null;
    public $user_id=null;
    public $auth_token=null;

    public function __construct() {
        $this->device_id = mt_rand(10000,mt_getrandmax());
    }

    public function setLoggedIn() {
        $this->isLoggedIn=true;
    }

    public function save(array $data) {
        $this->isLoggedIn = true;
        $this->user_id = $data['user_profile']['user_id'];
        $this->auth_token = $data['auth_token'];
    }

    public function setPhoneNumber($phone_number) {
        $this->phone_number = $phone_number;
    }

    public function loginWithAuthToken($auth_token) {
        $this->setLoggedIn();
        $this->auth_token = $auth_token;
    }

}
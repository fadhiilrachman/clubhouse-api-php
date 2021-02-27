<?php

/**
* Clubhouse Private API
* @author Fadhiil Rachman <https://www.instagram.com/fadhiilrachman>
* @version 1.1.0
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

    function __construct() {
        $this->device_id = mt_rand(10000,mt_getrandmax());
    }

    public function logged_in() {
        $this->isLoggedIn=true;
    }

    public function required_login() {
        if(!$this->isLoggedIn) {
            throw new ClubhouseException('not logged in', 400);
        }
    }

    public function save(array $data) {
        $this->logged_in();
        $this->auth_token = $data['auth_token'];
        $this->user_id = $data['user_profile']['user_id'];
    }

    public function setPhoneNumber($phone_number) {
        $this->phone_number = $phone_number;
    }

    public function loginWithAuthToken($auth_token) {
        $this->logged_in();
        $this->auth_token = $auth_token;
    }

}
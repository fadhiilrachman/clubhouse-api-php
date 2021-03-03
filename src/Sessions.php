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

    public $phone_number=null;
    public $user_id=null;
    public $auth_token=null;

    public function required_login() {
        if(!$this->isLoggedIn) {
            throw new ClubhouseException('not logged in', 400);
        }
    }

    public function logged_in($data) {
        if(!$this->isLoggedIn) {
            $this->isLoggedIn=true;
        }
        $this->auth_token = $data['auth_token'];
        if( is_array($data) && array_key_exists('user_profile', $data) ) {
            $this->user_id = $data['user_profile']['user_id'];
        }
        $this->headers['Authorization'] = 'Token ' . $this->auth_token;
        $this->headers['CH-DeviceID'] = mt_rand(10000, mt_getrandmax());
        $this->headers['CH-UserID'] = $this->user_id;
    }

    public function setPhoneNumber($phone_number) {
        $this->phone_number = $phone_number;
    }

    public function loginWithAuthToken($auth_token) {
        $this->logged_in([
            'auth_token' => $auth_token
        ]);
        $this->logged_in($this->me());
    }

}
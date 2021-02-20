<?php

/**
* Clubhouse Private API
* @author Fadhiil Rachman <https://www.instagram.com/fadhiilrachman>
* @version 0.0.1
* @license https://github.com/fadhiilrachman/clubhouse-api-php/blob/master/LICENSE The BSD-3-Clause License
*/

namespace FadhiilRachman\Clubhouse;

use \FadhiilRachman\Clubhouse\Constants;
use \FadhiilRachman\Clubhouse\ClubhouseException;

class Clubhouse extends ClubhouseException
{
	protected $phone_number;
	protected $isLoggedIn=false;

	public $device_id;
	public $user_id;
	public $auth_token;
	public $access_token;
	public $refresh_token;
	public $clubhouseData;
	
	protected $header=[];
	
	function __construct($phone_number)
	{
		$this->phone_number = $phone_number;
		$this->device_id = mt_rand(10000,mt_getrandmax());
	}

	public function saveUserData(array $data) {
		$this->isLoggedIn		= true;
		$this->user_id			= $data['user_profile']['user_id'];
		$this->auth_token		= $data['auth_token'];
		$this->access_token		= $data['access_token'];
		$this->refresh_token	= $data['refresh_token'];
	}

	public function startPhoneNumberAuth() {
		$post=[
			'phone_number'	    => $this->phone_number
		];
		$request = $this->request('/start_phone_number_auth', [], $post);
		if( !array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('user_not_found', 404);
			return ;
		}
		return $request;
	}

	public function completePhoneNumberAuth($verification_code) {
        $post=[
            'phone_number'	    => $this->phone_number,
            'verification_code'	=> $verification_code
        ];
        $request = $this->request('/complete_phone_number_auth', [], $post);
        if( array_key_exists('auth_token', $request) ) {
			$this->saveUserData($request);
            return $request;
        } else {
            throw new ClubhouseException('login failed', 500);
            return ;
        }
	}

	public function follow($user_id, $user_ids=null, $source=4, $source_topic_id=null) {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$post=[
			'user_id'			=> $user_id,
			'user_ids'			=> $user_ids,
			'source'			=> $source,
			'source_topic_id'	=> $source_topic_id,
		];
		$request = $this->request('/follow', [], $post);
		if( array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('follow failed', 500);
			return ;
		}
		return $request;
	}

	public function unfollow($user_id) {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$post=[
			'user_id'			=> $user_id,
		];
		$request = $this->request('/unfollow', [], $post);
		if( array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('unfollow failed', 500);
			return ;
		}
		return $request;
	}

	public function me($return_blocked_ids=null, $timezone_identifier='Asia/Jakarta', $return_following_ids=null) {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$post=[
			'return_blocked_ids'			=> $return_blocked_ids,
			'timezone_identifier'			=> $timezone_identifier,
			'return_following_ids'			=> $return_following_ids
		];
		$request = $this->request('/me', [], $post);
		if( array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('me failed', 500);
			return ;
		}
		return $request;
	}

	public function getProfile($user_id) {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$post=[
			'user_id'			=> $user_id
		];
		$request = $this->request('/get_profile', [], $post);
		if( array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('get profile failed', 500);
			return ;
		}
		return $request;
	}

	public function getFollowing($user_id, $page_size=50, $page=1) {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$param=[
			'user_id'			=> $user_id,
			'page_size'			=> $page_size,
			'page'				=> $page
		];
		$request = $this->request('/get_following', $param, []);
		if( array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('get following failed', 500);
			return ;
		}
		return $request;
	}

	public function getFollowers($user_id, $page_size=50, $page=1) {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$param=[
			'user_id'			=> $user_id,
			'page_size'			=> $page_size,
			'page'				=> $page
		];
		$request = $this->request('/get_followers', $param, []);
		if( array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('get followers failed', 500);
			return ;
		}
		return $request;
	}

	protected function request($endpoint, $param=0, $post=0) {
		$curl = curl_init();
		if ( !empty($post) ) {
			array_push($this->header, 'Content-Type: application/json; charset=utf-8');
			array_push($this->header, 'Accept: application/json; charset=utf-8');
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post, true) );
		}
		if($this->isLoggedIn) {
			array_push($this->header, 'CH-UserID: ' . $this->user_id);
			array_push($this->header, 'CH-DeviceID: ' . $this->device_id);
			array_push($this->header, 'Authorization: Token ' . $this->auth_token);
		}
		curl_setopt_array($curl, array(
			CURLOPT_URL                 => Constants::API_URL . $endpoint . ( $param ? '?'.http_build_query($param) : ''),
			CURLOPT_HTTPHEADER          => $this->header,
			CURLOPT_USERAGENT           => Constants::USER_AGENT,
			CURLOPT_RETURNTRANSFER		=> 1,
			CURLOPT_VERBOSE             => 0,
			CURLOPT_SSL_VERIFYHOST		=> 0,
			CURLOPT_SSL_VERIFYPEER		=> 0
		));
		$data = curl_exec($curl);
		if(!$data) {
			throw new ClubhouseException('cUrl has been crashed', 500);
			return ;
		}
		curl_close($curl);
		return json_decode($data, true);
	}

}
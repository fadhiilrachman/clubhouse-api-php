<?php

/**
* Clubhouse Private API
* @author Fadhiil Rachman <https://www.instagram.com/fadhiilrachman>
* @version 0.0.2
* @license https://github.com/fadhiilrachman/clubhouse-api-php/blob/master/LICENSE The BSD-3-Clause License
*/

namespace FadhiilRachman\Clubhouse;

use \FadhiilRachman\Clubhouse\Requests;
use \FadhiilRachman\Clubhouse\ClubhouseException;

class Clubhouse extends Requests
{
	
	protected $request;
	
	function __construct($phoneNumberOrAuthToken) {
		if( strlen($phoneNumberOrAuthToken) > 0 && strlen($phoneNumberOrAuthToken) <= 16 ) {
			$this->setPhoneNumber($phoneNumberOrAuthToken);
		} else {
			$this->loginWithAuthToken($phoneNumberOrAuthToken);
		}
	}

	public function startPhoneNumberAuth() {
		$post=[
			'phone_number' => $this->phone_number
		];
		$request = $this->post('/start_phone_number_auth', $post);
		if( is_array($request) && !array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('user_not_found', 404);
			return ;
		}
		return $request;
	}

	public function completePhoneNumberAuth($verification_code) {
        $post=[
            'phone_number' => $this->phone_number,
            'verification_code' => $verification_code
        ];
        $request = $this->post('/complete_phone_number_auth', $post);
        if( is_array($request) && array_key_exists('auth_token', $request) ) {
			$this->save($request);
            return $request;
        } else {
            throw new ClubhouseException('login failed', 500);
            return ;
        }
	}

	public function refreshToken($refresh_token) {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$post=[
			'refresh_token' => $refresh_token
		];
		$request = $this->post('/refresh_token', $post);
		if( is_array($request) && array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('refresh token failed', 500);
			return ;
		}
		return $request;
	}

	public function follow($user_id, $user_ids=null, $source=4, $source_topic_id=null) {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$post=[
			'user_id' => $user_id,
			'user_ids' => $user_ids,
			'source' => $source,
			'source_topic_id' => $source_topic_id,
		];
		$request = $this->post('/follow', $post);
		if( is_array($request) && array_key_exists('error_message', $request) ) {
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
			'user_id' => $user_id,
		];
		$request = $this->post('/unfollow', $post);
		if( is_array($request) && array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('unfollow failed', 500);
			return ;
		}
		return $request;
	}

	public function inviteFromWhitelist($user_id) {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$post=[
			'user_id' => $user_id,
		];
		$request = $this->post('/invite_from_waitlist', $post);
		if( is_array($request) && array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('invite from waitlist failed', 500);
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
			'return_blocked_ids' => $return_blocked_ids,
			'timezone_identifier' => $timezone_identifier,
			'return_following_ids' => $return_following_ids
		];
		$request = $this->post('/me', $post);
		if( is_array($request) && array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('me failed', 500);
			return ;
		}
		return $request;
	}

	public function getOnlineFriends($user_id) {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$request = $this->get('/get_online_friends');
		if( is_array($request) && array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('get online friends failed', 500);
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
			'user_id' => $user_id
		];
		$request = $this->post('/get_profile', $post);
		if( is_array($request) && array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('get profile failed', 500);
			return ;
		}
		return $request;
	}

	public function getEvents($is_filtered=true, $page_size=25, $page=1) {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$param=[
			'channel' => $channel,
			'channel_id' => null
		];
		$request = $this->get('/get_events', $param);
		if( is_array($request) && array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('get events failed', 500);
			return ;
		}
		return $request;
	}

	public function getChannel($channel) {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$post=[
			'channel' => $channel,
			'channel_id' => null
		];
		$request = $this->post('/get_channel', $post);
		if( is_array($request) && array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('get channel failed', 500);
			return ;
		}
		return $request;
	}

	public function getChannels() {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$request = $this->get('/get_channels');
		if( is_array($request) && array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('get channels failed', 500);
			return ;
		}
		return $request;
	}

	public function getAllTopics() {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$request = $this->get('/get_all_topics');
		if( is_array($request) && array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('get all topics failed', 500);
			return ;
		}
		return $request;
	}

	public function getClub($club_id, $source_topic_id=null) {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$post=[
			'club_id' => $club_id,
			'source_topic_id' => $source_topic_id
		];
		$request = $this->post('/get_club', $post);
		if( is_array($request) && array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('get club failed', 500);
			return ;
		}
		return $request;
	}

	public function getClubMembers($club_id, $return_followers=false, $return_members=true, $page_size=50, $page=1) {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$param=[
			'club_id' => $club_id,
			'return_followers' => $return_followers,
			'return_members' => $return_members,
			'page_size' => $page_size,
			'page' => $page
		];
		$request = $this->get('/get_club_members', $param);
		if( is_array($request) && array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('get club members failed', 500);
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
			'user_id' => $user_id,
			'page_size' => $page_size,
			'page' => $page
		];
		$request = $this->get('/get_following', $param);
		if( is_array($request) && array_key_exists('error_message', $request) ) {
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
			'user_id' => $user_id,
			'page_size' => $page_size,
			'page' => $page
		];
		$request = $this->get('/get_followers', $param);
		if( is_array($request) && array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('get followers failed', 500);
			return ;
		}
		return $request;
	}

	public function searchUsers($query, $followers_only=false, $following_only=false, $cofollows_only=false) {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$post=[
			'query' => $query,
			'followers_only' => $followers_only,
			'following_only' => $following_only,
			'cofollows_only' => $cofollows_only
		];
		$request = $this->post('/search_users', $post);
		if( is_array($request) && array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('search users failed', 500);
			return ;
		}
		return $request;
	}

	public function joinChannel($channel) {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$post=[
			'channel' => $channel,
			'attribution_source' => 'feed',
			'attribution_details' => base64_encode(json_encode(
				['is_explore'=>false,'rank'=>1]
			))
		];
		$request = $this->post('/join_channel', $post);
		if( is_array($request) && array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('join channel failed', 500);
			return ;
		}
		return $request;
	}

	public function leaveChannel($channel) {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$post=[
			'channel' => $channel,
			'channel_id' => null
		];
		$request = $this->post('/leave_channel', $post);
		if( is_array($request) && array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('leave channel failed', 500);
			return ;
		}
		return $request;
	}

	public function createChannel($topic='', $user_ids=[], $is_private=false, $is_social_mode=false) {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$post=[
			'topic' => $topic,
			'user_ids' => $user_ids,
			'is_private' => $is_private,
			'is_social_mode' => $is_social_mode
		];
		$request = $this->post('/create_channel', $post);
		if( is_array($request) && array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('create channel failed', 500);
			return ;
		}
		return $request;
	}

	public function endChannel($channel) {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$post=[
			'channel' => $channel,
			'channel_id' => null
		];
		$request = $this->post('/end_channel', $post);
		if( is_array($request) && array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('end channel failed', 500);
			return ;
		}
		return $request;
	}

	public function makeChannelPublic($channel) {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$post=[
			'channel' => $channel,
			'channel_id' => null
		];
		$request = $this->post('/make_channel_public', $post);
		if( is_array($request) && array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('make channel public failed', 500);
			return ;
		}
		return $request;
	}

	public function makeChannelSocial($channel) {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$post=[
			'channel' => $channel,
			'channel_id' => null
		];
		$request = $this->post('/make_channel_social', $post);
		if( is_array($request) && array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('make channel social failed', 500);
			return ;
		}
		return $request;
	}

	public function acceptSpeakerInvite($channel, $user_id) {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$post=[
			'channel' => $channel,
			'user_id' => $user_id
		];
		$request = $this->post('/accept_speaker_invite', $post);
		if( is_array($request) && array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('accept speaker invite failed', 500);
			return ;
		}
		return $request;
	}

	public function rejectSpeakerInvite($channel, $user_id) {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$post=[
			'channel' => $channel,
			'user_id' => $user_id
		];
		$request = $this->post('/reject_speaker_invite', $post);
		if( is_array($request) && array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('reject speaker invite failed', 500);
			return ;
		}
		return $request;
	}

	public function inviteSpeaker($channel, $user_id) {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$post=[
			'channel' => $channel,
			'user_id' => $user_id
		];
		$request = $this->post('/invite_speaker', $post);
		if( is_array($request) && array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('invite speaker failed', 500);
			return ;
		}
		return $request;
	}

	public function uninviteSpeaker($channel, $user_id) {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$post=[
			'channel' => $channel,
			'user_id' => $user_id
		];
		$request = $this->post('/uninvite_speaker', $post);
		if( is_array($request) && array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('uninvite speaker failed', 500);
			return ;
		}
		return $request;
	}

	public function muteSpeaker($channel, $user_id) {
		if(!$this->isLoggedIn) {
			throw new ClubhouseException('not logged in', 400);
			return ;
		}
		$post=[
			'channel' => $channel,
			'user_id' => $user_id
		];
		$request = $this->post('/mute_speaker', $post);
		if( is_array($request) && array_key_exists('error_message', $request) ) {
			throw new ClubhouseException('mute speaker failed', 500);
			return ;
		}
		return $request;
	}

}
<?php

class UserAccount {
	protected $uid;
	protected $domain;

	public function __construct($get, $post, $cookie) {
		$this->uid = $this->set_uid($post, $cookie);
		$this->domain = $this->set_domain($post);
	}

	private function set_uid($post, $cookie) {
		if(isset($post['user_id'])){
			$user_id = $post['user_id']; 
			$array = explode('::', $user_id);
			$uid = $array[0];
		} elseif (isset($cookie['uid'])) {
			$uid = $cookie['uid'];
		} else {
			$uid = '0';
		}

		return $uid;
	}

	private function set_domain($post) {
		if(isset($post['launch_presentation_return_url'])){
			$return_url = $post['launch_presentation_return_url']; 
			$array = explode('/external_tool', $return_url);
			$domain = $array[0];
		} else {
			$domain = 'https://app.schoology.com';
		}

		return $domain;
	}

	public function get_uid() {
		return $this->uid;
	}

	public function get_domain() {
		return $this->domain;
	}
}
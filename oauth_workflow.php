<?php
require_once('schoology_php_sdk/SchoologyApi.class.php');

class OauthWorkflow {
	protected $storage;
	protected $config;
	protected $user;
	protected $access_token;
	protected $oauth_token;
	protected $schoology;
	protected $schoology_auth;

	public function __construct($storage, $config, $user) {
		$this->storage = $storage->get_database_connection();
		$this->config = $config;
		$this->user = $user;
		$this->schoology = new SchoologyApi($this->config->get_app_consumer_key(), $this->config->get_app_consumer_secret());
	}

	public function need_request_token($get) {
		if(!isset($get['oauth_token'])) {
			return TRUE;
		}
	}

	public function fetch_request_token() {
		$request_token = $this->schoology->api('/oauth/request_token');
		$parsed_request_token = array();
		parse_str($request_token->result, $parsed_request_token);
		return $parsed_request_token;
	}

	public function auth_url($request_token) {
		$params = array(
		    'oauth_callback=' . urlencode('https://' . '589f1a81.ngrok.io' . $_SERVER['REQUEST_URI']),
		    'oauth_token=' . urlencode($request_token['oauth_token']),
		    'user_id=' . urlencode($this->user->get_uid()),
		);

		$query_string = implode('&', $params);
		return $this->user->get_domain() . '/oauth/authorize?'  . $query_string;
	}

	public function need_access_token() {
		$token = $this->storage->getAccessTokens($this->user->get_uid());
		if($token) {
			$this->access_token = $token;
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function fetch_access_token() {
		return $this->access_token;
	}

	public function get_new_access_token() {
		$access_token = $this->schoology_auth->api('/oauth/access_token');
		$parsed_access_token = array();
		parse_str($access_token->result, $parsed_access_token);
		return $parsed_access_token;
	}

	public function check_access_token() {
		$token_key = $this->access_token['token_key'];
 		$token_secret = $this->access_token['token_secret'];
 		$uid = $this->user->get_uid();
  		try{
		    $this->schoology->apiResult('users/' . $uid);
		}
	  	catch(Exception $e){
	  		if($e->getCode() == 401){
	  			$this->storage->revokeAccessTokens($uid);
	    	}
  		}
	}

	public function need_oauth_token($get) {
		$oauth_token = $this->fetch_oauth_token($get);
		$request_credentials = $this->get_request_credentials_from_oauth($oauth_token);
		$uid = $request_credentials['uid'];
		return $this->need_access_token($uid);
	}

	public function fetch_oauth_token($get) {
		return $get['oauth_token'];
	}

	public function get_request_credentials_from_oauth($oauth_token) {
		return $this->storage->getUidAndSecret($oauth_token);
	}

	public function get_user_schoology_api_connection($request_credentials) {
		$this->schoology_auth = new SchoologyApi($this->config->get_app_consumer_key(), $this->config->get_app_consumer_secret(), '', $request_credentials['token_key'], $request_credentials['token_secret']);
	}

	public function replace_request_tokens_with_access_tokens($access_tokens) {
		$this->storage->requestToAccessTokens($this->user->get_uid(), $access_tokens['oauth_token'], $access_tokens['oauth_token_secret']);
	}

	public function store_request_token($request_token) {
		$this->storage->saveRequestTokens($this->user->get_uid(), $request_token['oauth_token'], $request_token['oauth_token_secret']);
	}
}
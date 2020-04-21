<?php
require_once('GetDataForm.php');
require_once('config.php');
require_once('oauth_storage.php');
require_once('oauth_workflow.php');
require_once('users.php');

$user = new UserAccount($_GET, $_POST, $_COOKIE);
$config = new ConfigSettings();
$storage = new OauthStorage();
$oauth = new OauthWorkflow($storage, $config, $user);

if($oauth->need_access_token()){
	if($oauth->need_request_token($_GET)) {
		$request_token = $oauth->fetch_request_token();
		$oauth->store_request_token($request_token);
		header('Location: ' . $oauth->auth_url($request_token));
		exit;
	} 
	$oauth_token = $oauth->fetch_oauth_token($_GET);
	$request_credentials = $oauth->get_request_credentials_from_oauth($oauth_token);
	$oauth->get_user_schoology_api_connection($request_credentials);
	$access_tokens = $oauth->get_new_access_token();
	$oauth->replace_request_tokens_with_access_tokens($access_tokens);

} else {
	$oauth->fetch_access_token();
	$oauth->check_access_token();
}
<?php
require_once('config.php');
require_once('oauth_storage.php');
require_once('oauth_workflow.php');
require_once('users.php');


$user = new UserAccount($_GET, $_POST, $_COOKIE);
$config = new ConfigSettings();
$storage = OauthStorageFactory::create();
$oauth = new OauthWorkflow($storage, $config, $user);

$uid = $user->get_uid();
$domain = $user->get_domain();

if($oauth->need_request_token($_GET)) { 
	$oauth->fetch_request_token();
	header('Location: ' . $oauth->get_auth_url());
	exit;
}

if($oauth->need_oauth_token($_GET)) {
	$oauth->fetch_oauth_token($_GET);
}

if($oauth->have_access_token()) {
	$oauth->check_access_token();
}
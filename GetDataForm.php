<?php
require_once('schoology_php_sdk/SchoologyApi.class.php');
require_once('App_OauthStorage.class.php');
require_once('oauth_storage.php');

function get_data_from_api(){
	$form_data = get_form_data_from_post();
	$oauth_storage = new OauthStorage;
	$storage = $oauth_storage->get_database_connection();
	$oauth_tokens = $storage->getAccessTokens($form_data['uid']);	
	$consumer_key = 'a6694d0888207de681da8b99039f28d205e6e948b';
	$consumer_secret = 'ea51feadb0f2885bd0a5cebd162d38a1';
	$schoology = new SchoologyApi($consumer_key, $consumer_secret, '', $oauth_tokens['token_key'], $oauth_tokens['token_secret']);

	$requests = user_api_information_form_submit($form_data);
	$response_data = execute_api_requests($requests, $schoology);
	return($response_data); 
}

function get_form_data_from_post(){
	$form_data = [
		'courses' => $_POST['courses'],
		'groups' => $_POST['groups'],
		'uid' => $_POST['uid'],
		'domain' => $_POST['domain'],
	];

	return $form_data;
}

function user_api_information_form_submit($form_data){
	$requests = [];
	$uid = $form_data['uid'];
	
	if($form_data['courses']) {
		$request_courses = [
			'endpoint' => '/users/' . $uid . '/sections',
			'data_type' => 'courses',
		];
		$requests[] = $request_courses;
	}
	if($form_data['groups']) {
		$request_groups = [
			'endpoint' => '/users/' . $uid . '/groups',
			'data_type' => 'groups',
		];
		$requests[] = $request_groups;
	}

	return $requests;
}

function execute_api_requests($requests, $schoology) {
	foreach ($requests as $request) {
		$output[$request['data_type']] = $schoology->api($request['endpoint'])->result;		
	}
	return $output;
}
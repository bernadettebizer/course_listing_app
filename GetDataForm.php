<?php
require_once('schoology_php_sdk/SchoologyApi.class.php');
require_once('App_OauthStorage.class.php');

function get_data_from_api(){
	$form_data = get_form_data_from_post();
	$storage = make_db_connection();
	$uid = get_uid();
	$oauth_tokens = $storage->getAccessTokens($uid);
	
	$consumer_key = 'a6694d0888207de681da8b99039f28d205e6e948b';
	$consumer_secret = 'ea51feadb0f2885bd0a5cebd162d38a1';
	$schoology = new SchoologyApi($consumer_key, $consumer_secret, '', $oauth_tokens['token_key'], $oauth_tokens['token_secret']);

	$requests = user_api_information_form_submit($form_data);

	return execute_api_requests($requests, $schoology);
}

function get_form_data_from_post(){
	$form_data = [
		'courses' => $_POST['courses'],
		'groups' => $_POST['groups'],
	];

	return $form_data;
}

function make_db_connection(){
		$db_host = '127.0.0.1';
		$db_user = 'root';
		$db_pass = 'Bcb315!!';
		$db_name = 'auth';
		$db = new PDO('mysql:dbname='.$db_name.';host='.$db_host, $db_user, $db_pass);
		$storage = new App_OauthStorage($db);
		return $storage;
	}

function user_api_information_form_submit($form_data){
	//$uid = $form_data['uid'];
	$requests = [];
	
	if($form_data['courses']) {
		$request_courses = [
			'endpoint' => '/users/51105630/sections',
			'data_type' => 'courses',
		];
		$requests[] = $request_courses;
	}
	if($form_data['groups']) {
		$request_groups = [
			'endpoint' => '/users/51105630/groups',
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

function get_uid() {
	if($_POST['user_id']){
		$user_id = $_POST['user_id']; 
		$array = explode('::', $user_id);
		$uid = $array[0];
	} else {
		$uid = 0;
	}

	return $uid;
}
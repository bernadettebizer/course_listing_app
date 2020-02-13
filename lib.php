<?php

require_once('schoology_php_sdk/SchoologyApi.class.php');

function process_and_validate_form_data(){
	$form_data = get_form_data_from_post();
	
	if (user_api_information_form_validate($form_data)) {
		return user_api_information_form_submit($form_data);
	} else {
		return NULL;
	}
}

function get_form_data_from_post(){
	$form_data = array();
	$form_data['uid'] = $_POST['uid'];
	$form_data['key'] = $_POST['key'];
	$form_data['secret'] = $_POST['secret'];

	return $form_data;
}

function user_api_information_form_validate($form_data){
	if(!$form_data['uid']||!$form_data['key']||!$form_data['secret']) {
		return FALSE;
	} else {
		return TRUE;
	}
}

function user_api_information_form_submit($form_data){
	$output = [];

	$schoology = new SchoologyApi($form_data['key'], $form_data['secret']);
	$uid = $form_data['uid'];
	
	if($_POST['courses']) {
		$course_api_result = $schoology->api('/users/' . $uid . '/sections');
		$output['courses'] = $course_api_result->result;
	}
	if($_POST['groups']) {
		$group_api_result = $schoology->api('/users/' . $uid . '/groups');
		$output['groups'] = $group_api_result->result;
	}
	return $output;
}

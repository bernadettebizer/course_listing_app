<?php
require_once('schoology_php_sdk/SchoologyApi.class.php');

function user_api_information_form_validate(){
	$uid = $_POST['uid'];
	$key = $_POST['key'];
	$secret = $_POST['secret'];

	if(is_null($uid)||is_null($key)||is_null($secret)) {
		print("UID, Key, and Secret are all required fields");
		exit;
	} else {
		user_api_information_form_submit($uid, $key, $secret);
	}
}

function user_api_information_form_submit($uid, $key, $secret){
	$domain = 'schoology.com';

	$schoology = new SchoologyApi($key, $secret);

	$api_result = $schoology->api('/users/' . $uid . '/sections');
	$output = '<b>Courses</b>';
	$output .= '<ul>';
 
	// Cycle through the result and print each course
	$has_courses = FALSE;
	foreach($api_result->result->section as $section){
  		$has_courses = TRUE;
  		$output .= '<li>' . $section->course_title . ':' . $section->section_title . '</li>';
	}
 
	// If no courses were found print an 'empty' message
	if(!$has_courses){
  		$output .= '<li>No courses were found for this user.</li>';
	}
 
	$output .= '</ul>';

	print($output);
}

user_api_information_form_validate();
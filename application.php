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
		user_api_information_form_submit($uid, $key, $secret, $options);
	}
}

function user_api_information_form_submit($uid, $key, $secret){
	$domain = 'schoology.com';

	$schoology = new SchoologyApi($key, $secret);

	$output = '<body style="background-color:powderblue; padding:25px; font-family:Georgia;">';

	if(isset($_POST['courses'])||isset($_POST['groups'])){
		if($_POST['courses']) {
			$output .= list_courses($uid, $schoology);
		}
		if($_POST['groups']) {
			$output .= list_groups($uid, $schoology);
		}
	} else {
		$output .= '<p>You did not select to list either groups or courses.</p>';
	}

	$output .='</body>';

	print($output);
}

function list_courses($uid, $schoology) {
	$api_result = $schoology->api('/users/' . $uid . '/sections');
	$output = '<b>Courses</b>';
	$output .= '<ul>';
 
	// Cycle through the result and print each course
	$has_courses = FALSE;
	foreach($api_result->result->section as $section){
  		$has_courses = TRUE;
  		$output .= '<li>' . $section->course_title . ': ' . $section->section_title . '</li>';
	}
 
	// If no courses were found print an 'empty' message
	if(!$has_courses){
  		$output .= '<li>No courses were found for this user.</li>';
	}
 
	$output .= '</ul>';

	return $output;
}

function list_groups($uid, $schoology) {
	$api_result = $schoology->api('/users/' . $uid . '/groups');
	$output = '<b>Groups</b>';
	$output .= '<ul>';
 
	// Cycle through the result and print each group
	$has_groups = FALSE;
	foreach($api_result->result->group as $group){
  		$has_groups = TRUE;
  		$output .= '<li>' . $group->title;
	}
 
	// If no courses were found print an 'empty' message
	if(!$has_groups){
  		$output .= '<li>No groups were found for this user.</li>';
	}
 
	$output .= '</ul>';

	return $output;
}

user_api_information_form_validate();
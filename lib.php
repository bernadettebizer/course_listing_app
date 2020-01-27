<?php

require_once('schoology_php_sdk/SchoologyApi.class.php');

function process_and_validate_form_data(){
	$form_data = get_form_data_from_post();
	
	if (user_api_information_form_validate($form_data)) {
		user_api_information_form_submit($form_data);
	} else {
		exit;
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
	if(is_null($form_data['uid'])||is_null($form_data['key'])||is_null($form_data['secret'])) {
		print("UID, Key, and Secret are all required fields");
		return FALSE;
	} else {
		return TRUE;
	}
}

function user_api_information_form_submit($form_data){
	$output = '<body style="background-color:powderblue; padding:25px; font-family:Georgia;">';

	$schoology = new SchoologyApi($form_data['key'], $form_data['secret']);
	$uid = $form_data['uid'];
	
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

	//avoid string concatination in a loop
	//use template or string builder, research php options

	$output .='</body>';

	print($output);
}

function list_courses($uid, $schoology) {
	$api_result = $schoology->api('/users/' . $uid . '/sections');
	$output = '<b>Courses</b>';
	$output .= format_lists($api_result, 'course');
 
	return $output;
}

function list_groups($uid, $schoology) {
	$api_result = $schoology->api('/users/' . $uid . '/groups');
	$output = '<b>Groups</b>';
	$output .= format_lists($api_result, 'group');

	return $output;
}

function format_lists($api_result, $list_type) {
	$has_courses_or_groups = FALSE;
	$output = '<ul>';
	
	if($list_type == 'group') {
		//cycles through a list of user groups
		foreach((array)$api_result->result->group as $group){
  			$has_courses_or_groups = TRUE;
  			$output .= '<li>' . $group->title;
		}
	} else {
		//cyles through a list of user courses
		foreach((array)$api_result->result->section as $section){
	  		$has_courses_or_groups = TRUE;
	  		$output .= '<li>' . $section->course_title . ': ' . $section->section_title . '</li>';
		}
	}
	//how could I avoid doing this/ make it so that I can call this function without it needing to know the list type, just do the list, DRY 

	// If no courses or groups were found print an 'empty' message
	if(!$has_courses_or_groups){
  		$output .= '<li>No ' . $list_type . 's were found for this user.</li>';
	}

	$output .= '</ul>';

	return $output;
}
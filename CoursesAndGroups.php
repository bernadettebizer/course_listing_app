<?php

require_once('schoology_php_sdk/SchoologyApi.class.php');

class CoursesAndGroups {
	public function process_and_validate_form_data(){
		$form_data = $this->get_form_data_from_post();

		if ($this->user_api_information_form_validate($form_data)) {
			$schoology = new SchoologyApi($form_data['key'], $form_data['secret']);
			$requests = $this->user_api_information_form_submit($form_data);
			return $this->execute_api_requests($requests, $schoology);
		} else {
			return NULL;
		}
	}

	public function get_form_data_from_post(){
		$form_data = [];
		$form_data['uid'] = $_POST['uid'];
		$form_data['key'] = $_POST['key'];
		$form_data['secret'] = $_POST['secret'];
		$form_data['courses'] = $_POST['courses'];
		$form_data['groups'] = $_POST['courses'];

		return $form_data;
	}

	public function user_api_information_form_validate($form_data){
		if(!$form_data['uid']||!$form_data['key']||!$form_data['secret']) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function execute_api_requests($requests, $schoology) {
		foreach ($requests as $request) {
			$output[$request['data_type']] = $schoology->api($request['endpoint'])->result;		
		}
		return $output;
	}

	public function user_api_information_form_submit($form_data){
		$uid = $form_data['uid'];
		$requests = [];
		
		if($form_data['courses']) {
			$request_courses['endpoint'] = '/users/' . $uid . '/sections';
			$request_courses['data_type'] = 'courses';
			$requests[] = $request_courses;
		}
		if($form_data['groups']) {
			$request_groups['endpoint'] = '/users/' . $uid . '/groups';
			$request_groups['data_type'] = 'groups';
			$requests[] = $request_groups;
		}

		return $requests;
	}
}


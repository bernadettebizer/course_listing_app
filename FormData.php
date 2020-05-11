<?php
require_once('schoology_php_sdk/SchoologyApi.class.php');

class FormData {
	protected $courses;
	protected $groups;
	protected $uid;
	protected $domain;
	protected $storage;
	protected $config;
	protected $schoology;

	public function __construct($post, $storage, $config) {
		if (isset($post['courses'])) {
			$this->courses = $post['courses'];
		}
		if (isset($post['groups'])) {
			$this->groups = $post['groups'];
		}
		$this->uid = $post['uid'];
		$this->domain = $post['domain'];
		$this->storage = $storage->get_database_connection();
		$this->config = $config;

		$access_tokens = $this->storage->getAccessTokens($this->uid);
		$this->schoology = new SchoologyApi($this->config->get_app_consumer_key(), $this->config->get_app_consumer_secret(), '', $access_tokens['token_key'], $access_tokens['token_secret']);
	}

	private function course_request_data() {
		if (isset($this->courses)) {
			$course_request_data = [
				'endpoint' => '/users/' . $this->uid . '/sections',
				'data_type' => 'courses',
			];
			return $course_request_data;
		}
	}

	private function group_request_data() {
		if (isset($this->groups)) {
			$group_request_data = [
				'endpoint' => '/users/' . $this->uid . '/groups',
				'data_type' => 'groups',
			];
			return $group_request_data;
		}
	}

	public function get_request_data() {
		$request_data = [];
		if (isset($this->courses)) {
			$request_data[] = $this->course_request_data();
		}
		if (isset($this->groups)) {
			$request_data[] = $this->group_request_data();
		}
		return $request_data;
	}

	public function get_requested_data_from_api() {
		if (isset($this->courses) || isset($this->groups)) {
			$requests = $this->get_request_data();
			foreach ($requests as $request) {
				$output[$request['data_type']] = $this->schoology->api($request['endpoint'])->result;		
			}
			return $output;
		} else {
			return NULL;
		}
	}
}
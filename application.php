<?php
require_once('CourseCollection.php');
require_once('GroupCollection.php');
require_once('config.php');
require_once('oauth_storage.php');
require_once('FormData.php');

$config = new ConfigSettings();
$storage = new OauthStorage();
$form_data = new FormData($_POST, $storage, $config);

$title = 'User Information App';
$courses_requested = isset($_POST['courses']);
$groups_requested = isset($_POST['groups']);
$domain = $_POST['domain'];

$results = $form_data->get_requested_data_from_api();

if($courses_requested) {
	$courses = new CourseCollection($results['courses']);
	if($courses->course_data_is_valid()) {
		$section_list = $courses->get_sections();
	}
}

if($groups_requested) {
	$groups = new GroupCollection($results['groups']);
	if($groups->group_data_is_valid()) {
		$group_list = $groups->get_groups();
	}
}
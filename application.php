<?php
require_once('CourseCollection.php');
require_once('GetDataForm.php');
require_once('GroupCollection.php');

$title = 'User Information App';
$courses_requested = isset($_POST['courses']);
$groups_requested = isset($_POST['groups']);
$domain = $_POST['domain'];

$results = get_data_from_api();

if($courses_requested) {
	$courses = new CourseCollection($results['courses']);
	if($courses->validate_course_data()) {
		$section_list = $courses->get_sections();
	}
}

if($groups_requested) {
	$groups = new GroupCollection($results['groups']);
	if($groups->validate_group_data()) {
		$group_list = $groups->get_groups();
	}
}
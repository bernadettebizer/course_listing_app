<?php
require_once('Courses.php');
require_once('GetDataForm.php');
require_once('Groups.php');

$title = 'User Information App';
$courses_requested = isset($_POST['courses']);
$groups_requested = isset($_POST['groups']);

$results = get_data_from_api();

if($courses_requested) {
	$courses = new Courses($results['courses']);
	if($courses->validate_course_data()) {
		$parsed_courses = $courses->parse_course_data();
	}
}

if($groups_requested) {
	$groups = new Groups($results['groups']);
	if($groups->validate_group_data()) {
		$parsed_groups = $groups->parse_group_data();
	}
}
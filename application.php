<?php
require_once('lib.php');

$title = 'Course List';
$courses_requested = isset($_POST['courses']);
$groups_requested = isset($_POST['groups']);
$results = process_and_validate_form_data();
if($results){
	$courses = $results['courses'];
	$groups = $results['groups'];
}
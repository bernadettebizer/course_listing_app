<?php
require_once('CoursesAndGroups.php');

$title = 'User Information App';
$courses_requested = isset($_POST['courses']);
$groups_requested = isset($_POST['groups']);
$coursesAndGroups = new CoursesAndGroups;
$results = $coursesAndGroups->process_and_validate_form_data();
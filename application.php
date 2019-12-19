<?php
// // Replace this with the path to the Schoology PHP SDK
require_once('schoology_php_sdk/SchoologyApi.class.php');
 
// // Replace these values with your application's consumer key and secret
// $consumer_key = 'consumerkey';
// $consumer_secret = 'consumersecret';
// // Initialize the Schoology class
// $schoology = new SchoologyApi($consumer_key, $consumer_secret);
 
// // Initialize session handling
// session_start();

// require_once('schoology_php_sdk/SchoologyApi.class.php');
// $consumer_key = '642b561c5d9f3b69ee249816c63aaaf805dc06a04';
// $consumer_secret = '8bc25b5fe2b7261197aabc8295275b1c';
 
// Initialize the session
//session_start();
 
// Make sure a user is logged in. Users shouldn't be accessing 
// this page directly without first passing through login.php
// if(!isset($_SESSION['schoology']['uid'])){
//   print 'No user information was found when loading this page. Please try loading this application from within Schoology.';
//   exit;
// }

//***how to make this form visible in the ui??***



function user_api_information_form_validate(){
	$uid = $_POST['uid'];
	$key = $_POST['key'];
	$secret = $_POST['secret'];

	if(is_null($uid)||is_null($key)||is_null($secret)) {
		print("UID, Key, and Secret are all required fields");
		exit;
	} else {
		user_api_information_form_submit();
	}
}

function user_api_information_form_submit(){
	$values = $form_state['values'];

	$uid = $values['uid'];
	$key = $values['key'];
	$secret = $values['secret'];
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
}

user_api_information_form_validate();

//$uid = $_SESSION['schoology']['uid'];
 
// // Use the Schoology domain that the user is logged in to
// $domain = $_SESSION['schoology']['domain'];

 
// Initialize a database connection. Replace these values 
// with information needed to access your database
// $db_host = 'localhost:3306';
// $db_user = 'root';
// $db_pass = 'Bcb315!!';
// $db_name = 'auth';
// $db = new PDO('mysql:dbname='.$db_name.';host='.$db_host, $db_user, $db_pass);

// // Change this to the path of your token datastore adapter
// require_once('App.OauthStorage.class.php');
// $oauth_store = new App_OauthStorage($db);
 
// // get user's app session timestamp
// $app_session_timestamp = $_SESSION['session_created'];
 
// // Retrieve and set the user's OAuth request key and 
// // request secret in the SchoologyApi object.
// // Also check if user has an active Schoology web session.
// $schoology->authorize($oauth_store, $uid, $app_session_timestamp);

// Get a list of the user's course sections from the API
// $api_result = $schoology->api('/users/' . $uid . '/sections');
// $output = '<b>Courses</b>';
// $output .= '<ul>';
 
// // Cycle through the result and print each course
// $has_courses = FALSE;
// foreach($api_result->result->section as $section){
//   $has_courses = TRUE;
//   $output .= '<li>' . $section->course_title . ':' . $section->section_title . '</li>';
// }
 
// // If no courses were found print an 'empty' message
// if(!$has_courses){
//   $output .= '<li>No courses were found for this user.</li>';
// }
 
// $output .= '</ul>';
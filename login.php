<?php

require_once('schoology_php_sdk/SchoologyApi.class.php');
 
// Replace these values with your application's consumer key and secret
$consumer_key = '642b561c5d9f3b69ee249816c63aaaf805dc06a04';
$consumer_secret = '8bc25b5fe2b7261197aabc8295275b1c';
// Initialize the Schoology class
$schoology = new SchoologyApi($consumer_key, $consumer_secret);
 
// Initialize session handling
session_start();

// Read the incoming login information.
$login = $schoology->validateLogin();
 
// If the last step failed, then either no information 
// was received or it was invalid.
if(!$login){
  // Stop script execution
  print 'No login information was received. Try loading this application again from within Schoology.';
  exit;
}

// If our session already has a stored ID but it's 
// different from what we received, restart the session.
if(isset($_SESSION['schoology']['uid']) && $_SESSION['schoology']['uid'] != $login['uid']){
  session_destroy();
  session_start();
}
 
// The session might already be set if the user is accessing 
// this application again without logging out of Schoology.
// Only set the session information if not already present
if(!isset($_SESSION['schoology']['uid'])){
  $_SESSION['schoology'] = $login;
  // later on, during authorization, we will compare this timestamp to the user's Schoology web session timestamp
  // to ensure that the user still has an active web session
  $_SESSION['session_created'] = time();
}

header('Location: ' . $_REQUEST['RelayState']);
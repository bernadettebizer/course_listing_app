<?php 
require_once('GetDataForm.php');

require_once('config.php');
require_once('oauth_storage.php');
require_once('oauth_workflow.php');
require_once('users.php');

$user = new UserAccount($_GET, $_POST); //to get and store user info
$config = new ConfigSettings();
$storage = new OauthStorage();
$oauth = new OauthWorkflow($storage, $config, $user);

$uid = $user->get_uid();
$domain = $user->get_domain();

if($oauth->need_request_token($_GET)) {
	$request_token = $oauth->fetch_request_token();
	$oauth->store_request_token($request_token, $uid);
	header('Location: ' . $oauth->auth_url($request_token));
	exit;
} 

$oauth_token = $oauth->fetch_oauth_token($_GET);
$request_credentials = $oauth->get_request_credentials_from_oauth($oauth_token);

if($oauth->need_access_token($request_credentials['uid'])) {
	$oauth->get_user_schoology_api_connection($request_credentials);
	$access_tokens = $oauth->get_new_access_token();
	$oauth->replace_request_tokens_with_access_tokens($access_tokens);

if(!$oauth->need_access_token($uid)){
	$oauth->fetch_access_token();
	$oauth->check_access_token();
}

?>

<!doctype html>
<html lang="en">

<head>
	<title>User Information App</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
<h1>User Information App</h1>

<form action="template.php" method="post">
<span><h3>Check all information needed on user with given UID</h3></span>
<input type="checkbox" name="courses"> List Courses<br>
<input type="checkbox" name="groups"> List Groups<br><br>
<input type="hidden" name="uid" value=<?=$uid?>>
<input type="hidden" name="domain" value=<?=$domain?>>
<input class="submit_button" type="submit">
</form>

</body>
</html>
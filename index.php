<?php 
require_once('schoology_php_sdk/SchoologyApi.class.php');
require_once('App_OauthStorage.class.php');
require_once('GetDataForm.php');

$consumer_key = 'a6694d0888207de681da8b99039f28d205e6e948b';
$consumer_secret = 'ea51feadb0f2885bd0a5cebd162d38a1';

$schoology = new SchoologyApi($consumer_key, $consumer_secret);
$storage = make_db_connection();
$uid = get_uid();

session_start();


if(!isset($_GET['oauth_token'])){
  // Get request token
	$api_result = $schoology->api('/oauth/request_token');

	// Parse the query-string-formatted result
	$result = array();
	parse_str($api_result->result, $result);

	// Store the request token in our DB
	$storage->saveRequestTokens($uid, $result['oauth_token'], $result['oauth_token_secret']);

	$params = array(
	    'oauth_callback=' . urlencode('https://' . '589f1a81.ngrok.io' . $_SERVER['REQUEST_URI']),
	    'oauth_token=' . urlencode($result['oauth_token']),
	    'user_id=' . urlencode($uid),
	);

	$query_string = implode('&', $params);

	header('Location: ' . 'https://magicdistrict.schoology.com' . '/oauth/authorize?'  . $query_string);
} 
else {
  // Get the existing record from our DB
	$oauth_token = $_GET['oauth_token'];
	$uid = $storage->getUid($oauth_token)['uid'];

	$request_tokens = $storage->getRequestTokens($uid); //why is this not returning the same uid as from line 15?

	$schoology = new SchoologyApi($consumer_key, $consumer_secret, '', $request_tokens['token_key'], $request_tokens['token_secret']);

	$api_result = $schoology->api('/oauth/access_token');

	// Parse the query-string-formatted result
	$result = array();
	parse_str($api_result->result, $result);
	 
	// Update our DB to replace the request tokens with access tokens
	$storage->requestToAccessTokens($uid, $result['oauth_token'], $result['oauth_token_secret']);
}

?>

<!doctype html>
<html lang="en">

<head>
	<title>User Information App</title>
	<h1>User Information App</h1>
	<link rel="stylesheet" href="style.css">
</head>

<body>

<form action="template.php" method="post">
<span><h3>Check all information needed on user with given UID</h3></span>
<input type="checkbox" name="courses"> List Courses<br>
<input type="checkbox" name="groups"> List Groups<br><br>
<input type="hidden" name="uid" value=<?=$uid?>>
<input class="submit_button" type="submit">
</form>

</body>
</html>
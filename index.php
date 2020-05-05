<?php include_once("authenticate.php");?>

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
<input type="hidden" name="uid" value=<?php echo($uid)?>>
<input type="hidden" name="domain" value=<?php echo($domain)?>>
<input class="submit_button" type="submit">
</form>

</body>
</html>
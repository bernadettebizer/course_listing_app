<?php include_once("application.php") ?>

<!doctype html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title><?=$title?></title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<?if(is_null($results)){?>
		<p>UID, Key, and Secret are all required fields.</p>
	<?} elseif($courses_requested || $groups_requested) {
		if($courses_requested){?>
			<b>Courses</b>
			<ul>
			<? foreach($courses->section as $section) {?>
				<li><?=$section->course_title?>:<?=$section->section_title?></li>
			<?}?>
			</ul>
		<?}
		if($groups_requested){?>
			<b>Groups</b>
			<ul>
			<? foreach($groups->group as $group) {?>
				<li><?=$group->title?></li>
			<?}?>
			</ul>
		<?}

	} else {?>
		<p>You did not select to list either groups or courses.</p>
	<?}?>

</body>

</html>





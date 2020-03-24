<?php include_once("application.php") ?>

<!doctype html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>User Information App</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<?if(!$courses_requested && !$groups_requested){?>
		<p>You did not select to list either groups or courses.</p>
	<?} else {
		if($courses_requested){?>
			<b>Courses</b>
			<ul>
			<? foreach($parsed_courses as $section) {?>
				<li><?=$section['course_title']?>: <?=$section['section_title']?></li>
			<?}?>
			</ul>
		<?}
		if($groups_requested){?>
			<b>Groups</b>
			<ul>
			<? foreach($results['groups']->group as $group) {?>
				<li><?=$group->title?></li>
			<?}?>
			</ul>
		<?}
	}
	?>

</body>

</html>





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
			<? foreach($section_list as $section) {?>
				<li><a href=<?=$domain . '/course/' . $section['section_nid']?> target="_blank"><?=$section['course_title']?>: <?=$section['section_title']?></a></li>
			<?}?>
			</ul>
		<?}
		if($groups_requested){?>
			<b>Groups</b>
			<ul>
			<? foreach($group_list as $group) {?>
				<li><a href=<?=$domain . '/group/' . $group['group_nid']?> target="_blank"><?=$group['group_title']?></a></li>
			<?}?>
			</ul>
		<?}
	}
	?>

</body>

</html>





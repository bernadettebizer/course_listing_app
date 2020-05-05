<?php include_once("application.php"); ?>

<!doctype html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>User Information App</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<?php if(!$courses_requested && !$groups_requested){?>
		<p>You did not select to list either groups or courses.</p>
	<?php } else {
		if($courses_requested){?>
			<b>Courses</b>
			<ul>
			<?php foreach($section_list as $section){?>
				<li><a href= <?php echo($domain . '/course/' . $section['section_nid'])?> target="_blank"><?php echo($section['course_title'])?>: <?php echo($section['section_title'])?></a></li>
			<?php }?>
			</ul>
		<?php }
		if($groups_requested){?>
			<b>Groups</b>
			<ul>
			<?php foreach($group_list as $group) {?>
				<li><a href=<?php echo($domain . '/group/' . $group['group_nid'])?> target="_blank"><?php echo($group['group_title'])?></a></li>
			<?php }?>
			</ul>
		<?php }
	}
	?>

</body>

</html>





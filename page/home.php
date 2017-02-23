<?php
	setlocale(LC_ALL, 'fr_FR');
	setlocale(LC_TIME, "fr_FR");
	setlocale (LC_TIME, 'fr_FR.utf8','fra'); 

	$query = "SELECT *, UNIX_TIMESTAMP(date) AS timestamp FROM posts WHERE status = 1 ORDER BY date DESC LIMIT 10";
	$rst = mysqli_query($cnt, $query);

	while($arr = mysqli_fetch_array($rst)) {
		// echo '<a href="/post/'.slugify($arr['title']).'">' . $arr['title'] . '</a><br />';
		echo '<a href="/post/'.$arr['id'].'">' . $arr['title'] . '</a><br />';
		echo date('D j M Y', $arr['timestamp']) . '<br/>' . $arr['description'];
		echo '<br /><br />';
	}

	$query = "SELECT * FROM tags WHERE status = 1";
	$rst = mysqli_query($cnt, $query);

	while($arr = mysqli_fetch_array($rst)) {
		echo '<a href="/tags/'.$arr['id'].'">' . $arr['tag'] . '</a><br />';
		echo '<br /><br />';
	}
	//$query = "SELECT posts.* , tags.* FROM posts INNER JOIN tags ON posts.id = tags.id";
?>
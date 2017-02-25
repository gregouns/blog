<?php
	setlocale(LC_ALL, 'fr_FR');
	setlocale(LC_TIME, "fr_FR");
	setlocale (LC_TIME, 'fr_FR.utf8','fra');

		$query = "SELECT * , UNIX_TIMESTAMP(date) AS timestamp FROM posts WHERE status = 1 ORDER BY date DESC LIMIT 10";
		$rst = mysqli_query($cnt, $query);

		while($arr = mysqli_fetch_array($rst)) {
			// echo '<a href="/post/'.slugify($arr['title']).'">' . $arr['title'] . '</a><br />';
			echo 'Title: <a style="color:orange;" href="/post/'.$arr['id'].'">' . $arr['title'] . '</a><br />';
			echo 'Date: ' . date('D j M Y', $arr['timestamp']) . '<br/> Description: ' . $arr['description'] . '<br/>';
			echo '<br/><br/>';

		}

?>
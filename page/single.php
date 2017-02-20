<?php

	$post = $_GET['post'];

	$query = "SELECT *, UNIX_TIMESTAMP(date) AS timestamp FROM posts WHERE status = 1 AND id = '{$post}' LIMIT 1";
	$rst = mysqli_query($cnt, $query);

	while($arr = mysqli_fetch_array($rst)) {
		echo '<a href="/post/'.$arr['id'].'">' . $arr['title'] . '</a><br />';
		echo $arr['description'] . '<br />';
		echo date('D j M Y', $arr['timestamp']);
		echo '<br /><br />';
	}

?>
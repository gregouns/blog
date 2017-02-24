<?php

	$post = $_GET['post'];

	$query = "SELECT *, UNIX_TIMESTAMP(date) AS timestamp FROM posts WHERE status = 1 AND post_id = '{$post}' LIMIT 1";
	$rst = mysqli_query($cnt, $query);

	while($arr = mysqli_fetch_array($rst)) {
		echo 'Title: <a style="color:orange;" href="/post/'.$arr['post_id'].'">' . $arr['title'] . '</a><br />';
		echo 'Description: ' . $arr['description'] . '<br />';
		echo 'Date: ' . date('D j M Y', $arr['timestamp']);
		echo '<br /><br />';
	}
	

?>
<?php
	$tag = $_GET['tag'];
	$query = "SELECT *, UNIX_TIMESTAMP(date) AS timestamp FROM posts, tags WHERE tag_id = tag_post_id AND tag_id = '{$tag}'";
	$rst = mysqli_query($cnt, $query);

	while($arr = mysqli_fetch_array($rst)) {
		echo 'Tag: <a href="/tags/'.$arr['tag_id'].'">' . $arr['tag'] . '</a><br />';
		echo 'Post: <a style="color:orange;" href="/post/'.$arr['post_id'].'">' . $arr['title'] . '</a><br />';
		echo 'Description: ' . $arr['description'] . '<br />';
		echo 'Date: ' . date('D j M Y', $arr['timestamp']);
		echo '<br /><br />';
	}

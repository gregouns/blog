<?php
	$tags = $_GET['tag'];
	$query = "SELECT post.* FROM post JOIN post_tags ON post.post_id = post_tag.post_id JOIN tag ON tags.tag_id = post_tag.tag_id where status = 1";
	$strt = mysqli_query($cnt, $query);

	while($arr = mysqli_fetch_array($rst)) {
		echo '<a href="/tags/'.$arr['tag_id'].'">' . $arr['tag'] . '</a><br />';
		echo '<a href="/post/'.$arr['post_id'].'">' . $arr['title'] . '</a><br />';
		echo $arr['description'] . '<br />';
		echo date('D j M Y', $arr['timestamp']);
		echo '<br /><br />';
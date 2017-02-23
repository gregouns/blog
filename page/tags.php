<?php
	$tags = $_GET['tag'];
	$query = "SELECT tags.* tags WHERE tag_id = '{$tag}' LIMIT 1";

	$rst = mysqli_query($cnt, $query);

	while($arr = mysqli_fetch_array($rst)) {
		echo '<a href="/tags/'.$arr['tag_id'].'">' . $arr['tags'] . '</a><br />';
		echo '<br /><br />';
	}
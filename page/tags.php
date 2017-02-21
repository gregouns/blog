<?php
	
	$tags = $_GET['tags'];
	$query = "SELECT tags FROM table_tags WHERE id = '{$post}'";

	$rst = mysqli_query($cnt, $query);

	while($arr = mysqli_fetch_array($rst)) {
		echo '<a href="/post/'.$arr['id'].'">' . $arr['tags'] . '</a><br />';
		echo $arr['tags'] . '<br />';
		echo '<br /><br />';
	}
<?php
	$tag = $_GET['tag'];
	$query = "SELECT * FROM tags WHERE tag_url = '{$tag}'";
	$rst = mysqli_query($cnt, $query);

	$cnt2 = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
	mysqli_query($cnt2, "SET NAMES 'utf8'");

	while ($arr = mysqli_fetch_array($rst)) {
		$post_id = $arr['post_id'];
		$query2 = "SELECT *, UNIX_TIMESTAMP(date) AS timestamp FROM posts WHERE id = '{$post_id}'";
		$rst2 = mysqli_query($cnt2, $query2);
			while ($arr2 = mysqli_fetch_array($rst2)) {
				echo 'tag: <a href="/tag/'.$arr['tag'].'">' .$arr['tag'] . '</a><br/>';
				echo 'title: <a href="/post/'.$arr2['id'].'">' .$arr2['title'] . '</a><br/>';
				echo 'description: ' . $arr2['description'] . '<br/>';
				echo date('D j M Y', $arr2['timestamp']);
				echo '<br/><br/>';
			}
	}


<?php
	$cnt2 = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
	mysqli_query($cnt2, "SET NAMES 'utf8'");

	$post = $_GET['post'];

	$query = "SELECT *, UNIX_TIMESTAMP(date) AS timestamp FROM posts WHERE url = '{$post}' AND status = 1 LIMIT 1";
	$rst = mysqli_query($cnt, $query);
		while($arr = mysqli_fetch_array($rst)) {
			echo 'Title: <a style="color:orange;" href="/post/'.$arr['url'].'">' . $arr['title'] . '</a><br />';
			echo 'Description: ' . $arr['description'] . '<br />';
			echo 'Date: ' . date('D j M Y', $arr['timestamp']);
			echo '<br />';
			$query2 = "SELECT * FROM tags AS t,posts_tags AS pt WHERE pt.post_id = '{$arr['id']}'AND pt.tag_id = t.id";
			$rst2 = mysqli_query($cnt2,$query2);
			while($arr2 = mysqli_fetch_array($rst2)) {
				echo 'tag: <a href = "/tags/'.$arr2['url'].'">' . $arr2['tag'] . '</a><br />';
			}
		}

?>
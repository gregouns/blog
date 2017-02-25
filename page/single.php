<?php
	$cnt2 = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
	mysqli_query($cnt2, "SET NAMES 'utf8'");

	$post = $_GET['post'];

	$query = "SELECT *, UNIX_TIMESTAMP(date) AS timestamp FROM posts WHERE id = '{$post}' AND status = 1 LIMIT 1";
	$rst = mysqli_query($cnt, $query);

	if ( mysqli_num_rows($rst) == 1 ) {
		while($arr = mysqli_fetch_array($rst)) {
			$query2 = "SELECT * FROM tags WHERE post_id = '{$arr['id']}'";
			$rst2 = mysqli_query($cnt2,$query2);
			while($arr2 = mysqli_fetch_array($rst2)) {
				$arr_tags[] = '<a href="/tags/'.$arr2['tag_url'].'">'.$arr2['tag'].'</a>';
			}
			
			echo 'Title: <a style="color:orange;" href="/post/'.$arr['id'].'">' . $arr['title'] . '</a><br />';
			echo 'Description: ' . $arr['description'] . '<br />';
			echo 'Date: ' . date('D j M Y', $arr['timestamp']);
			echo 'Tags: ' . implode(', ', $arr_tags);

			echo '<br /><br />';
		}
	}
	else {
		header('Location: /');
	}

?>
<?php
	$cnt2 = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
	mysqli_query($cnt2, "SET NAMES 'utf8'");
	$cnt3 = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
	mysqli_query($cnt3, "SET NAMES 'utf8'");

	$post = $_GET['post'];

	$query = "SELECT *, UNIX_TIMESTAMP(date) AS timestamp FROM posts WHERE url = '{$post}' AND status = 1 LIMIT 1";
	$rst = mysqli_query($cnt, $query);
		while($arr = mysqli_fetch_array($rst)) {
			echo 'Title: <a style="color:orange;" href="/post/'.$arr['url'].'">' . $arr['title'] . '</a><br />';
			echo 'Description: ' . $arr['description'] . '<br />';
			echo 'Date: ' . date('D j M Y', $arr['timestamp']) . '<br />';
			$query2 = "SELECT * FROM tags AS t,posts_tags AS pt WHERE pt.post_id = '{$arr['id']}'AND pt.tag_id = t.id";
			$rst2 = mysqli_query($cnt2,$query2);
			echo 'tag: ';
			while($arr2 = mysqli_fetch_array($rst2)) {
				echo '<a href = "/tags/'.$arr2['url'].'">' . $arr2['tag'] . '</a>,';
			}
			$query3 = "SELECT * FROM categories AS c,posts_cats AS pc WHERE pc.post_id = '{$arr['id']}'AND pc.cat_id = c.id";
			$rst3 = mysqli_query($cnt3,$query3);
			echo '<br/>categorie: ';
			while($arr3 = mysqli_fetch_array($rst3)) {
			echo '<a style="color:green;" href = "/categorie/'.$arr3['url'].'">' . $arr3['name'] . '</a>,';
			}
		}

?>
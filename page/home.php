<?php
	setlocale(LC_ALL, 'fr_FR');
	setlocale(LC_TIME, "fr_FR");
	setlocale (LC_TIME, 'fr_FR.utf8','fra');
		$cnt2 = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
		mysqli_query($cnt2, "SET NAMES 'utf8'");
		$cnt3 = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
		mysqli_query($cnt3, "SET NAMES 'utf8'");

		$query = "SELECT * , UNIX_TIMESTAMP(date) AS timestamp FROM posts WHERE status = 1 ORDER BY date DESC LIMIT 10";
		$rst = mysqli_query($cnt, $query);
		while($arr = mysqli_fetch_array($rst)) {
			$query2 = "SELECT * ,t.url AS turl FROM tags AS t,posts_tags AS pt WHERE pt.post_id = '{$arr['id']}' AND pt.tag_id = t.id";
			$rst2 = mysqli_query($cnt2,$query2);
			while($arr2 = mysqli_fetch_array($rst2)) {
				echo 'tag: <a href = "/tags/'.$arr2['turl'].'">' . $arr2['tag'] . '</a><br/>';
			}
			$query3 = "SELECT * ,c.url AS curl FROM categories AS c,posts_cats AS pc WHERE pc.post_id = '{$arr['id']}' AND pc.cat_id = c.id";
			$rst3 = mysqli_query($cnt3,$query3);
			while($arr3 = mysqli_fetch_array($rst3)) {
				echo 'categorie:<a style="color:green;" href = "/categorie/'.$arr3['curl'].'">' . $arr3['name'] . '</a><br/>';
			}
			echo 'Title: <a style="color:orange;" href="/post/'.$arr['url'].'">' . $arr['title'] . '</a><br />';
			echo 'Date: ' . date('D j M Y', $arr['timestamp']) . '<br/> Description: ' . $arr['description'] . '<br/>';
			echo '<br/><br/>';
		}

?>
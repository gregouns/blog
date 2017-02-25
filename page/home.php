<?php
	setlocale(LC_ALL, 'fr_FR');
	setlocale(LC_TIME, "fr_FR");
	setlocale (LC_TIME, 'fr_FR.utf8','fra');

	// $posts = array('id' ,'title','date','description','status');
	// $tags  =  array('id','id_post','tag','status');

		$cnt2 = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
		mysqli_query($cnt2, "SET NAMES 'utf8'");

		$query = "SELECT * , UNIX_TIMESTAMP(date) AS timestamp FROM posts WHERE status = 1 ORDER BY date DESC LIMIT 10";
		$rst = mysqli_query($cnt, $query);

		while($arr = mysqli_fetch_array($rst)) {
			$arr_tags = array();

			$query2 = "SELECT * FROM tags WHERE post_id = '{$arr['id']}'";
			$rst2 = mysqli_query($cnt2,$query2);
			while($arr2 = mysqli_fetch_array($rst2)) {
				$arr_tags[] = '<a href="/tags/'.$arr2['tag_url'].'">'.$arr2['tag'].'</a>';
			}


			// echo '<a href="/post/'.slugify($arr['title']).'">' . $arr['title'] . '</a><br />';
			echo 'Title: <a style="color:orange;" href="/post/'.$arr['id'].'">' . $arr['title'] . '</a><br />';
			echo 'Date: ' . date('D j M Y', $arr['timestamp']) . '<br/> Description: ' . $arr['description'] . '<br/>';
			echo 'Tags: ' . implode(', ', $arr_tags);
			echo '<br/><br/>';

		}

?>
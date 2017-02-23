<?php
	setlocale(LC_ALL, 'fr_FR');
	setlocale(LC_TIME, "fr_FR");
	setlocale (LC_TIME, 'fr_FR.utf8','fra');

	// $posts = array('id' ,'title','date','description','status');
	// $tags  =  array('id','id_post','tag','status');
		$query = "SELECT tag_id,tag, post_id, title, description, UNIX_TIMESTAMP(date) AS timestamp FROM tags, posts WHERE post_id = tag_id ORDER BY date DESC LIMIT 10";
		$rst = mysqli_query($cnt, $query);

		while($arr = mysqli_fetch_array($rst)) {
			// echo '<a href="/post/'.slugify($arr['title']).'">' . $arr['title'] . '</a><br />';
			echo 'Title: <a style="color:orange;" href="/post/'.$arr['post_id'].'">' . $arr['title'] . '</a><br />';
			echo 'Date: ' . date('D j M Y', $arr['timestamp']) . '<br/> Description: ' . $arr['description'] . '<br/>';
			echo 'Tag: <a href="/tags/'.($arr['tag_id']).'">' . $arr['tag'] . '</a><br />';
			echo '<br/><br/>';
		}



?>
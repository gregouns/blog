<?php
/**
 * Where $_GET['tag'] (string) is the tag name like "my-tag"
 */
	$tag = $_GET['tag'];

	// I want the post that has tag
	$query = "SELECT * 
		FROM 
			posts AS p, 
			posts_tags AS pt, 
			tags AS t 
		WHERE
			p.id = pt.post_id
			AND pt.tag_id = t.id
			AND t.url = '{$tag}'
	";
	echo $query;
	$rst = mysqli_query($cnt,$query);
	while($arr = mysqli_fetch_array($rst)) {
		echo 'tag: <a href = "/tags/'.$arr['pt.url'].'">' . $arr['tag'] . '</a><br/>';
		echo 'post: <a href = "/post/'.$arr['p.url'].'">' . $arr['post'] . '</a><br/>';
		echo 'description: ' . $arr['description'];
		echo 'date: ' . $arr['date'];
	}
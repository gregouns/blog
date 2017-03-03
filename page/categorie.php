<?php
$cat = $_GET['catego'];

	// I want the post that has catgerory
	$query = "SELECT * 
		FROM 
			posts AS p, 
			posts_tags AS pt, 
			categorie AS c 
		WHERE
			p.id = pt.post_id
			AND pt.cat_id = c.id
			AND c.name = '{$cat}'
	";
	$rst = mysqli_query($cnt,$query);
	while($arr = mysqli_fetch_array($rst)) {
		echo 'categorie: <a href = "/categorie/'.$arr['name'].'">' . $arr['name'] . '</a><br/>';
		echo 'post: <a href = "/post/'.$arr['title'].'">' . $arr['title'] . '</a><br/>';
		echo 'description: ' . $arr['description'];
		echo 'date: ' . $arr['date'] . '<br/>';
	}
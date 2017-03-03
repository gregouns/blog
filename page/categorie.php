<?php
$cat = $_GET['categorie'];


	// I want the post that has catgerory
$query = "SELECT * , p.url AS purl 
	FROM 
		posts AS p, 
		posts_tags AS pt, 
		categories AS c 
	WHERE
		p.id = pt.post_id
		AND pt.cat_id = c.id
		AND c.name = '{$cat}'
";
$rst = mysqli_query($cnt,$query);
while($arr = mysqli_fetch_array($rst)) {
	echo 'categorie: <a style="color:green;" href = "/categorie/'.$arr['url'].'">' . $arr['name'] . '</a><br/>';
	echo 'post: <a style="color:orange;" href = "/post/'.$arr['purl'].'">' . $arr['title'] . '</a><br/>';
	echo 'description: ' . $arr['description'] . '<br/>';
	echo 'date: ' . $arr['date'] . '<br/>';
}
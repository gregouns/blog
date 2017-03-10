<?php

$id_edit_post = $_GET['edit'];

if (isset($_POST['submit'])) {
	$title = $_POST['title'];
	$title = addslashes(strip_tags(trim($title)));
	$date  = $_POST['date'];
	$description = $_POST['description'];
	$description = addslashes(strip_tags(trim($description)));
	$query = "UPDATE posts 
		SET 
		`title` = '{$title}',
		`date`  = '{$date}',
		`description` = '{$description}',
		`url` = '".slugify($title)."'
		";
		var_dump($query);
	mysqli_real_escape_string($cnt, $title);
	$rst = mysqli_query($cnt,$query);

}

if (isset($_POST)) {
	$query = "SELECT * FROM posts WHERE id = '{$id_edit_post}'";
	$rst = mysqli_query($cnt,$query);
	while ($arr = mysqli_fetch_array($rst)) {
	$arr_post = array(
		'titre' => array(
			'name' => 'title',
			'type' => 'text',
			'value' => $arr['title']
			),
		'date' => array(
			'name' => 'date',
			'type' => 'datetime',
			'value' => $arr['date']
			),
		'description' => array(
			'name' => 'description',
			'type' => 'text',
			'value' => $arr['description']
			),
		);	
	}
	$query = "SELECT * FROM tags AS t,posts_tags AS pt,posts AS p  WHERE p.id = '{$id_edit_post}' AND pt.post_id = p.id AND pt.tag_id = t.id";
	echo $query;
	$rst = mysqli_query($cnt,$query);
	while ($arr = mysqli_fetch_array($rst)) {
		
	}

}

function generate($array) {
	$html = '';
	foreach ($array as $val) {
		$html .= '<div class = "form-group">';
		$html .= '<label>' . $val['name'] . '</label><br/>';
		$html .= '<input class="form-control" name = "'.$val['name'].'" type = "'.$val['type'].'" value = "'.$val['value'].'" /><br/></div>';
	}

	return $html;
}


echo '<form method="post" action="/edit/'.$id_edit_post.'">';
echo generate($arr_post);
echo '<button type="submit" name="submit" id="button" value="submit"  class="btn btn-primary">
		submit
	</button>';
echo '</form>';



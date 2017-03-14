<?php

$id_edit_post = $_GET['edit'];

if (isset($_POST['submit'])) {
	// update title, description and date
	$title = $_POST['title'];
	$title = addslashes(strip_tags(trim($title)));
	$date  = $_POST['date'];
	$description = $_POST['description'];
	$description = addslashes(strip_tags(trim($description)));
	var_dump($description);
	$query = "UPDATE posts 
		SET 
		`title` = '{$title}',
		`date`  = '{$date}',
		`description` = '{$description}',
		`url` = '".slugify($title)."'
		";
	mysqli_real_escape_string($cnt, $title);
	$rst = mysqli_query($cnt,$query);	
	// update tags
	$tag = $_POST['tag'];
	var_dump($tag);
	foreach ($tag as $val) {
	
		$query = "UPDATE tags 
		SET 
		`tag` = '{$val}',
		`url` = '".slugify($val)."'
		";
		mysqli_real_escape_string($cnt, $val);
		$rst = mysqli_query($cnt,$query);
	}
	$name = $_POST['name'];
	$query = "SELECT * FROM categories WHERE name = '{$name}'";
	var_dump($query);
	$rst = mysqli_query($cnt,$query);
	$cat_recup__id = mysqli_insert_id($cnt);
	$query = "UPDATE posts_cats
	SET
	`cat_id` = '{$cat_recup__id}'";
	$rst = mysqli_query($cnt,$query);
}

if (isset($_POST)) {
	// select title description and date
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
			)
		);
	$description = $arr['description'];
	}
	// select tag
	$query = "SELECT * FROM tags AS t,posts_tags AS pt,posts AS p  WHERE p.id = '{$id_edit_post}' AND pt.post_id = p.id AND pt.tag_id = t.id";
	$rst = mysqli_query($cnt,$query);
	while ($arr = mysqli_fetch_array($rst)) {
		$tag[] = $arr['tag'];
	}

	// select categories
	$query = "SELECT * FROM categories AS c, posts_cats AS pc, posts AS p WHERE p.id = '{$id_edit_post}' AND pc.post_id = p.id AND pc.cat_id = c.id";
	$rst = mysqli_query($cnt,$query);
	while ($arr = mysqli_fetch_array($rst)) {
		$name[] = $arr['name'];
		$arr_tree = buildTree();
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
echo '<div class = "form-group"><label>description</label><br/><textarea class="form-control" name = "description" type = "text">' .$description . ' </textarea><br/></div>';
if(isset($tag)){
	$tag = implode(",", $tag);
	echo '<div class = "form-group"><label>tag</label><br/><input class="form-control" name = "tag[]" type = "text" value = "'.$tag.'" /><br/></div>';
}
else {
	echo '<div class = "form-group"><label>tag</label><br/><input class="form-control" name = "tag[]" type = "text" value = "" /><br/></div>';
}
if(isset($name)){
 	foreach ($name as $key => $value) {
		echo '<div class="input-group">
				<label for="category">define your categories</label>';
				
		echo toSELECT($arr_tree, 0, 'category_parent[]',$value);
		var_dump($value);
		echo '<span class="input-group-btn">
			<button type="button" class="plus btn btn-success">
				<i class="glyphicon glyphicon-plus"></i>
			</button>
			<button type="button" class="minus btn btn-danger">
				<i class="glyphicon glyphicon-minus"></i>
			</button>
		</span>
		</div>
		<div id="anotherCategory"></div>

		<div class="clearfix"></div>
		<br />';
	}
}
else {
	echo '<div class="input-group">
				<label for="category">define your categories</label>';
		$arr_tree = buildTree();
		echo toSELECT($arr_tree, 0, 'category_parent[]');
		echo '<span class="input-group-btn">
			<button type="button" class="plus btn btn-success">
				<i class="glyphicon glyphicon-plus"></i>
			</button>
			<button type="button" class="minus btn btn-danger">
				<i class="glyphicon glyphicon-minus"></i>
			</button>
		</span>
		</div>
		<div id="anotherCategory"></div>

		<div class="clearfix"></div>
		<br />';
}
echo '<button type="submit" name="submit" id="button" value="submit"  class="btn btn-primary">
		submit
	</button>';
echo '</form>';
echo `<script type="text/javascript">
	$(document).ready(function() {
		$(document).on('click', '.plus', function(e) {
			e.preventDefault();
			var el = $(e.currentTarget);
			var clone = el.parent().parent().clone();
			$('#anotherCategory').append(clone);
			console.log($('#anotherCategory').find('.minus'));
			$('#anotherCategory').find('.minus').prop("disabled", false);
		});
		$(document).on('click', '.minus', function(e) {
			e.preventDefault();
			var el = $(e.currentTarget);
			el.parent().parent().remove();
		});
	});
</script>`;



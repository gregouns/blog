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
			),
		'description' => array(
			'name' => 'description',
			'type' => 'text',
			'value' => $arr['description']
			),
		);	
	}
	// select tag
	$query = "SELECT * FROM tags AS t,posts_tags AS pt,posts AS p  WHERE p.id = '{$id_edit_post}' AND pt.post_id = p.id AND pt.tag_id = t.id";
	$rst = mysqli_query($cnt,$query);
	while ($arr = mysqli_fetch_array($rst)) {
		if (!empty($arr)) {
			$arr_tag = array(
				'tag' => array(
					'name' => 'tag',
					'type' => 'text',
					'value' => $arr['tag']
				)
			);
		}
		else  {
			$arr['tag'] = 'sélectionnez un tag';
			$arr_tag = array(
				'tag' => array(
					'name' => 'tag',
					'type' => 'text',
					'value' => $arr['tag']
				)
			);
		}
	}

	// select categories
	$query = "SELECT * FROM categories AS c, posts_cats AS pc, posts AS p WHERE p.id = '{$id_edit_post}' AND pc.post_id = p.id AND pc.cat_id = c.id";
	echo $query;
	$rst = mysqli_query($cnt,$query);
	while ($arr = mysqli_fetch_array($rst)) {
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
echo generate($arr_tag);
echo '<div class="input-group">
		<label for="category">define your categories</label>';
echo toSELECT($arr_tree, 0, 'category_parent[]',$arr['name']);
echo '<span class="input-group-btn">
			<button type="button" class="plus btn btn-success">
				<i class="glyphicon glyphicon-plus"></i>
			</button>
			<button type="button" class="minus btn btn-danger" disabled="disabled">
				<i class="glyphicon glyphicon-minus"></i>
			</button>
		</span>
		</div>
	<div id="anotherCategory"></div>

	<div class="clearfix"></div>
	<br />
<button type="submit" name="submit" id="button" value="submit"  class="btn btn-primary">
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



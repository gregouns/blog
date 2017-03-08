<?php
$message = '';
$cnt2 = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
mysqli_query($cnt2, "SET NAMES 'utf8'");
$cnt3 = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
mysqli_query($cnt3, "SET NAMES 'utf8'");
$html2 = "";
$id = $_GET['edit'];

if (isset($_POST['update'])) {
	$title = $_POST['title'];
	$title = addslashes(strip_tags(trim($title)));
	$date  = $_POST['date'];
	$description = $_POST['description'];
	$description = addslashes(strip_tags(trim($description)));
	$tag = $_POST['tag'];
	$tag     = addslashes(strip_tags(trim($tag)));
	$query = "UPDATE posts 
		SET 
		title = '{$title}',
		date  = '{$date}',
		description = '{$description}'
		";
		var_dump($query);
	$rst = mysqli_query($cnt,$query);
	$query = "UPDATE tags 
		SET 
		tag = '{$tag}'
		";
		var_dump($query);
	$rst = mysqli_query($cnt,$query);
}

$query = "SELECT *, DATE_FORMAT(date,'%y-%m-%d') FROM posts AS p WHERE id = $id";
if ($rst = mysqli_query($cnt, $query)) {
	while($arr = mysqli_fetch_array($rst)) {
		$query2 = "SELECT GROUP_CONCAT(`tag` SEPARATOR ',') AS `tag` FROM tags AS t, posts_tags AS pt WHERE pt.post_id = '{$arr['id']}' AND t.id = pt.tag_id";
		$rst2 = mysqli_query($cnt2, $query2);
		while($arr2 = mysqli_fetch_array($rst2)) {
			$query3 = "SELECT name FROM categories AS c, posts_cats AS pc WHERE pc.post_id = '{$arr['id']}' AND c.id = pc.cat_id";
			$rst3 = mysqli_query($cnt3, $query3);
			while($arr3 = mysqli_fetch_array($rst3)) {
				$arr_tree = buildTree();
				$arr['date'] = substr($arr['date'], 0,10);
				$html = '<form method="post" action="/edit/'.$id.'">
							<div class="form-group">
								<label for="titre">titre</label>
								<input id="titre" class="form-control" name="title" type="text" value="'.$arr['title'] . '" />
							</div>
							<div class="form-group">
								<label for="date">date</label>
								<input id="date" class="form-control" name="date" type="datetime" value="'.$arr['date'] . '" />
							</div>
							<div class="form-group">
								<label for="description">description</label>
								<textarea id="description" class="form-control" name="description">'.$arr['description'] . '</textarea>
							</div>
							<div class="form-group">
								<label for="tag">tag</label>
								<input id="tag" class="form-control" name="tag" type="text" value="'.$arr2['tag'] . '" />
							</div>';
		    					$html2 .= '<label for="category">your categories</label><div class="input-group">'. toSELECT($arr_tree, 0, 'category_parent[]' , $arr3['name'])
								. '	<span class="input-group-btn">
								      	<button type="button" class="plus btn btn-success">
								        	<i class="glyphicon glyphicon-plus"></i>
								      	</button>
								      	<button type="button" class="minus btn btn-danger" disabled="disabled">
								        	<i class="glyphicon glyphicon-minus"></i>
								      	</button>
									</span>
							</div>
							<div id="anotherCategory"></div>';

							$html3 = '<div class="clearfix"></div>
							<br />
							<button type="submit" name="update" id="button" value="submit"  class="btn btn-primary">
						    	submit
						   	</button>
						</form>';
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
			}
		}
	}
	echo $html;
	echo $html2;
	echo $html3;

	echo $message = '<div class="alert alert-success">Votre post a bien été modifié</div>';
}
else {
	echo $message = '<div class="alert alert-danger">Un problème est survenu, veuillez contacter le webmaster</div>';
}
?>
	








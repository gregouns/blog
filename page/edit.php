<?php

if(isset($POST['title']) && isset($_POST['date']) && isset($_POST['description'])) {
    $query = "UPDATE posts SET
            `title` = '{$_POST['title']}',
            `date` = '{$_POST['date']}',
            `description` = '{$_POST['description']}'
        WHERE
            id = '{$id}'";
$rst = mysqli_query($cnt, $query);
}

$cnt2 = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
mysqli_query($cnt2, "SET NAMES 'utf8'");
$cnt3 = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
mysqli_query($cnt3, "SET NAMES 'utf8'");

$id = $_GET['edit'];

$query = "SELECT * FROM posts AS p WHERE id = $id";
$rst = mysqli_query($cnt, $query);
while($arr = mysqli_fetch_array($rst)) {
	$query2 = "SELECT * FROM tags AS t, posts_tags AS pt WHERE pt.post_id = '{$arr['id']}' AND t.id = pt.tag_id";
	$rst2 = mysqli_query($cnt2, $query2);
	while($arr2 = mysqli_fetch_array($rst2)) {
		$query3 = "SELECT * FROM categories AS c, posts_cats AS pc WHERE pc.post_id = '{$arr['id']}' AND c.id = pc.cat_id";
		$rst3 = mysqli_query($cnt3, $query3);
		while($arr3 = mysqli_fetch_array($rst3)) {
			echo '<form method="post" action="/post">
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
					</div>

					<button type="submit" name="submit" id="button" value="submit"  class="btn btn-primary">
		    			submit
		  			</button>
				</form>';
		}
	}
}
?>
	








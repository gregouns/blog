<?php
$id_edit_post = $_GET['edit_tag'];

$query = "SELECT * FROM tags AS t, posts_tags AS pt WHERE post_id = '{$id_edit_post}' AND pt.tag_id = t.id";
var_dump($query);
$rst = mysqli_query($cnt,$query);
while ($arr = mysqli_fetch_assoc($rst)) {
	$tag []= $arr['tag'];
}
if(isset($tag)){
	$tag = implode(',', $tag);
	var_dump($tag);
	echo '<form method="post" action="/edit_tag/'.$id_edit_post.'">
		<div class = "form-group">
		<label>tag</label><br/>
		<input class="form-control" name = "tag[]" type = "text" value = "'.$tag.'" /><br/>
		</div>';
}
else {
	echo '<form method="post" action="/edit_tag/'.$id_edit_post.'">
		<div class = "form-group">
		<label>tag</label><br/>
		<input class="form-control" name = "tag[]" type = "text" value = "" /><br/>
		</div>';
}
echo '<button type="submit" name="submit" id="button" value="submit"  class="btn btn-primary">
		submit
	</button>';
echo '</form>';

$tag = $_POST['tag'];
var_dump($tag);
if (isset($_POST['submit'])) {
	if(isset($tag)) {
		foreach ($tag as $key => $value) {
			$query = "UPDATE tags SET tag = $value, url = '".slugify($value)."'";
			var_dump($query);
			$rst = mysqli_query($cnt,$query);
		}
	}
}
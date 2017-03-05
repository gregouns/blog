<?php

$message = '';
$title = '';
if (isset($_POST['title'])) {
	$title = $_POST['title'];
}
$date = '';
if (isset($_POST['date'])) {
	$date = $_POST['date'];
}
$description = '';
if (isset($_POST['description'])) {
	$description = $_POST['description'];
}
$tag = '';
if(isset($_POST['tag'])) {
	$tag     = $_POST['tag'];
	$arr_tag = array();
	if ($tag != '') {
		$arr_tag = explode(',', $tag);
	}
}
$arr_cat = '';
if(isset($_POST['submit'])) {
	if(isset($_POST['catego'])) {
		if($_POST['catego'] > 0){
			$arr_cat = $_POST['catego'];
		}
	}
}

if ( $_POST ) {
	$_error = false;

	if ( isset($_POST['title']) && strlen($_POST['title']) == 0 ) {
		$_error = true;
		$msgError[] = 'Merci de renseigner le titre';
	}
	if (isset($_POST['date'])) {
		if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
			$_error = true;
			$msgError[] = 'Merci de renseigner la date au format YYYY-MM-DD';
		}
	}

	if ( $_error == false ) {
		$_POST['title'] = strip_tags($_POST['title']);
		$_POST['description'] = strip_tags($_POST['description']);
		// $flagpost = true;
		$query = "INSERT INTO
			posts (`id`, `title`, `url`,`date`, `description`, `status`)
			VALUES (
			NULL,
			'{$_POST['title']}',
			'".slugify($title)."',
			'{$_POST['date']}',
			'{$_POST['description']}',
			1
		)";
		if (mysqli_query($cnt, $query)) {
			$post_id = mysqli_insert_id($cnt);
				foreach ($arr_cat as $key => $cat) {
					$query_cat = "SELECT id FROM categories WHERE name = '{$cat}'";
					$rst_cat = mysqli_query($cnt,$query_cat);
					while ($arr2 = mysqli_fetch_array($rst_cat)) {
						$cat_id = $arr2['id'];
						$query_insert_cat = "INSERT INTO posts_cats (post_id, cat_id) VALUES ('{$post_id}', '{$cat_id}')";
						$rst_insert_cat = mysqli_query($cnt,$query_insert_cat);
					}
				}

				foreach ($arr_tag as $key => $tag) {
					$queryTagExist = "SELECT * FROM tags Where tag = '{$tag}'";
					$rstTagExist = mysqli_query($cnt,$queryTagExist);

					if(mysqli_num_rows($rstTagExist) > 0) {
						$queryTagRecup = "SELECT tags.id AS tid FROM tags Where tag = '{$tag}'";
						$rst = mysqli_query($cnt, $queryTagRecup);
						while($arr = mysqli_fetch_array($rst)) {
							$tag_id = $arr['tid'];
							echo $post_id . '<br/>';
							echo $tag_id . '<br/>';
							$queryTagRel = "INSERT INTO posts_tags (post_id, tag_id) VALUES ('{$post_id}', '{$arr['tid']}')";
							$rstTagRel = mysqli_query($cnt, $queryTagRel);
						}
					}
					else {
						$queryTagName = "INSERT INTO tags (id, tag, url, status) VALUES (NULL, '{$tag}', '".slugify($tag)."', 1)";
						if (mysqli_query($cnt, $queryTagName)) {
							$tag_id = mysqli_insert_id($cnt);
							$queryTagRel = "INSERT INTO posts_tags (post_id, tag_id) VALUES ('{$post_id}', '{$tag_id}')";
							$rstTagRel = mysqli_query($cnt, $queryTagRel);
						}
					}
			}
			$message = '<div class="alert alert-success">Votre post a bien été inséré</div>';
		}
		else {
				$message = '<div class="alert alert-danger">Un problème est survenu, veuillez contacter le webmaster</div>';
		}
	}
	else {
		$message = '<div class="alert alert-warning">Veuillez remplir tous les champs : <ul>';
		foreach ($msgError as $key => $value) {
			$message .= '<li>'.$value.'</li>';
		}
		$message .= '</ul></div>';
	}
}
$arr_tree = buildTree();
?>

<?php echo $message ?>
<form method="post" action="/post">
	<div class="form-group">
		<label for="titre">titre</label>
		<input id="titre" class="form-control" name="title" type="text" value="<?php if (isset($_POST['titre'])) {echo $_POST['title'];}?>" />
	</div>
	<div class="form-group">
		<label for="date">date</label>
		<input id="date" class="form-control" name="date" type="datetime" value="<?php if (isset($_POST['date'])) {echo $_POST['date'];}?>" />
	</div>
	<div class="form-group">
		<label for="description">description</label>
		<textarea id="description" class="form-control" name="description"><?php if (isset($_POST['description'])) {echo $_POST['description'];} ?></textarea>
	</div>
	<div class="form-group">
		<label for="tag">define your tags</label>
		<input id="tag" class="form-control" name="tag" type="text" value="<?php if (isset($_POST['tag'])) {echo $_POST['tag'];}?>" />
	</div>
  <div class="input-group">
    <?php echo toSELECT($arr_tree, 0, 'category_parent[]'); ?>
    <span class="input-group-btn">
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
  </button>
</form>

<script type="text/javascript">
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
</script>

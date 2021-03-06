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
	$tag = $_POST['tag'];
	$arr_tag = array();
	if ($tag != '') {
		$arr_tag = explode(',', $tag);
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
			posts (`id`,`title`,`date`,`description`,`status`) 
		VALUES (
			NULL,
			'{$_POST['title']}',
			'{$_POST['date']}',
			'{$_POST['description']}',
			1
		)";

		if (mysqli_query($cnt, $query)) {
			$post_id = mysqli_insert_id($cnt);
			if ( $arr_tag && count($arr_tag) > 0) {
				$counter = 0;
				$query = "INSERT INTO tags (`id`, `post_id`, `tag`, `tag_url`, `status`) VALUES";
				foreach ($arr_tag as $key => $tag) {
					if ( $tag != '' ) {
						$tag = strip_tags($tag);
						$query .= "(
							NULL,
							'{$post_id}',
							'{$tag}',
							'".slugify($tag)."',
							1
						)" . ( count($arr_tag) == $counter+1 ? '' : ', ' );
					}
					$counter++;
				}
				echo $query;
				if (mysqli_query($cnt, $query)) {
					$message = '<div class="alert alert-success">Votre post a bien été inséré</div>';
				}
				else {
					$message = '<div class="alert alert-danger">Un problème est survenu, veuillez contacter le webmaster</div>';
				}
			}
			else {
				$message = '<div class="alert alert-success">Votre post a bien été inséré</div>';
			}	
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
	
	<button>envoyer</button>
</form>
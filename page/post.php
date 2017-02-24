<?php

$message = '';
$title = '';
$all_tags_id = '';
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
if (isset($_POST['tag'])) {
	$tag = $_POST['tag'];
}

$post_tags = array_map('trim', explode(',', $tag)); //suppression des caractères invisible en debut et fin de chaine
$post_tags = array_map('strtolower', $post_tags); // pour les mots en minuscules

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

	$query = 'SELECT tag FROM tags';
	$rst = mysqli_query($cnt ,$query);
	while($arr = mysqli_fetch_assoc($rst)){
		$tagsdb[] = $arr["tag"];
	}
	foreach ($post_tags as $value) {
		if(!in_array($value, $tagsdb) && $value != "") {
			echo "test";
			$query = "INSERT INTO 
				tags(`tag_id`,`tag`,`status`)
				VALUES(
					NULL,
					'{$value}',
					1
			)";
			mysqli_query($cnt, $query);	
		}
		$query = "SELECT tag_id FROM tags where tag = '".$value."' ";
		echo $query;
		$rst   = mysqli_query($cnt, $query);
		$arr   = mysqli_fetch_assoc($rst);
		$all_tags_id .= $arr['tag_id'] . ",";
	}
	$all_tags_id = substr($all_tags_id,0,strlen($all_tags_id) - 1 );
		 

	if ( $_error == false ) {
		$_POST['title'] = strip_tags($_POST['title']);
		$_POST['description'] = strip_tags($_POST['description']);
		// $flagpost = true;
		$query = "INSERT INTO 
			posts (`post_id`,`tag_post_id`,`title`,`date`,`description`,`status`) 
		VALUES (
			NULL,
			'{$all_tags_id}',
			'{$_POST['title']}',
			'{$_POST['date']}',
			'{$_POST['description']}',
			1
		)";
		echo $query;
		if (mysqli_query($cnt, $query)) {
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
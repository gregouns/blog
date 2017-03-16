<?php

$id_edit_post = $_GET['edit'];
$arr_tree = buildTree();


/**
 * 1. Charger les données du post
 * 2. Charger les données des categories associées au posts
 * 3. Charger les données des tags associées au posts
 * 4. Afficher les données dans le formulaire
 * 5. On charge les données du post dans le formulaire
 * 6. On doit mettre à jour les données du post essentiellement
 * 	6.1 Je m'assure que je n'ai pas de données corrompues
 * 	6.2 Je m'assure que je n'ai pas de données vides dans les camps obligatoires (quels sont ils ???)
 * 	6.3 Je fais en sorte de regénérer un slug de titre (slugify du titre)
 * 	6.4 En cas d'erreur en 6.1 ou 6.2 je renvoi un message d'erreur
 * 	6.5 Je renvois un message de félicitation si l'insertion s'est bien réalisée
 * 7. J'affiche mes tags dans le formulaire
 * 	7.1 Mes tags existant doivent comporter une action javascript permettant de les retirer et doivent figurer dans le div des tags *
 * "actuels"
 *  7.2 Si je retire des tags précédents ajoutés par erreur je dois pouvoir les remettre
 *  7.3 Ajouter VISUELLEMENT un ou des tags
 * 		7.3.1 Si le tag est ajouté il est mis dans la liste des tags ajoutés
 * 		7.3.2 Si le tag est supprimé il doit disparaitre car non présent dans ma base de donnée
 * 		7.3.3 Si le tag existe déjà il faut alors ne pas l'ajouter mais glisser le tag existant de tous vers les tags ajoutés
 * 8. J'arriche mes categories dans le formulaire
 * 	8.1 Je dois duplique le select tant que j'ai des categories
 * 	8.2 Toutes les categories doivent être supprimables "x" à l'exception de la premiere sinon je fais disparaitre l'ajout de categorie
 * 9. Ajouter les tags selectionnés dans un input visible puis caché à terme
 * 10. Mettre les tags ID dans un tableau de type arrUpdateTagIds[] = id
 * 11. Mettre les tags NAMES dans un tableau de type arrUpdateTagNames[] = name
 * 
 **/

/**
 * 6. On doit mettre à jour les données du post essentiellement
 * */
if (isset($_POST['submit'])) {

	// 6.1 Je m'assure que je n'ai pas de données corrompues
	// 6.2 Je m'assure que je n'ai pas de données vides dans les camps obligatoires (quels sont ils ???)
	$title = $_POST['title'];
	$title = cleaner($title);
	$description = $_POST['description'];
	$description = cleaner($description);
	$date = $_POST['date'];
	$date = cleaner($date);

	// 6.3 Je fais en sorte de regénérer un slug de titre (slugify du titre)
	$title_url = slugify($title);

	$tags_names = $_POST['tags_names'];
	$tags_names = cleaner($tags_names);

	if(true) {
		echo 'ben oui';
	}
	else {
		echo 'ben non';
	}

	// 6.4 En cas d'erreur en 6.1 ou 6.2 je renvoi un message d'erreur
	if ($title != '') {
		if ($description != '') {
			if ($date != '') {
				$query = "UPDATE posts
					SET
					`title` = '{$title}',
					`date`  = '{$date}',
					`description` = '{$description}',
					`url` = '$title_url'
					";
				if (mysqli_query($cnt, $query)) {
					// 6.5 Je renvois un message de félicitation si l'insertion s'est bien réalisée
					echo '<div class="alert alert-success">Votre post a bien été modifié</div>';

					// 10. Mettre les tags ID dans un tableau de type arrUpdateTagIds[] = id
					$arrUpdateTagIds = explode(',', $_POST['tags_ids']);
					foreach ($arrUpdateTagIds as $id) {
						$query = "UPDATE posts_tags
							SET
							tag_id = $id
							WHERE
							post_id = $id_edit_post";
						$rst = mysqli_query($cnt, $query);
					}

					// 11. Mettre les tags NAMES dans un tableau de type arrUpdateTagNames[] = name
					$arrUpdateTagNames = explode(',',$tags_names);
					print_r($arrUpdateTagNames);
					foreach ($arrUpdateTagNames as $tag) {
						$query = "INSERT INTO tags
							(id,
							tag,
							url,
							status)
							VALUES
							(NULL,
							'$tag',
							'".slugify($tag)."',
							1)";
						print_r($query);
						$rst = mysqli_query($cnt, $query);
					}
					// print_r($arrUpdateTagIds);
					// print_r($arrUpdateTagNames);
				}

				else {
					echo '<div class="alert alert-danger">un problème est survenu , contactez le webmaster</div>';
				}
			}
			else {
				echo '<div class="alert alert-warning">veuillez rentrer une date valable</div>';
			}
		}
		else{
			echo '<div class="alert alert-warning">veuillez rentrer une description valable</div>';
		}
	}
	else {
		echo '<div class="alert alert-warning">veuillez rentrer un titre valable</div>';
	}
}



/**
 * 1. Charger les données du post
 * objet ou tableau
 * $arr->title // $arr['title'] 
 * 
 * $arr_post['title'] = $arr['title'];
 * */
$query = "SELECT * FROM posts where id = '{$id_edit_post}' LIMIT 1";
$rst = mysqli_query($cnt, $query);
$arr_post = mysqli_fetch_array($rst);


/**
 * 5. On charge les données du post dans le formulaire
 * On remplace la valeur de mon post dans $_POST si toutefois il n'existe pas
 * */
if (!isset($_POST) || sizeof($_POST) == 0) {
	$_POST = $arr_post;
}


/**
 * 2. Charger les données des categories associées au posts
 * -> $arr_categories[id] = name
 * */
$arr_categories = array();
$query = "SELECT 
		*
	FROM 
		categories AS c, 
		posts_cats AS pc 
	WHERE 
		c.id = pc.cat_id
		AND pc.post_id = '{$id_edit_post}'
";
$rst = mysqli_query($cnt, $query);
while ($arr = mysqli_fetch_array($rst)) {
	$arr_categories[$arr['id']] = $arr['name'];
}
// print_r($arr_categories);


/**
 * 3. Charger les données des tags associées au posts
 * -> $arr_tags[id] = name
 * */
$arr_tags = array();
$query = "SELECT 
		*
	FROM 
		tags AS t, 
		posts_tags AS pt 
	WHERE 
		t.id = pt.tag_id
		AND pt.post_id = '{$id_edit_post}'
";
$rst = mysqli_query($cnt, $query);
while ($arr = mysqli_fetch_array($rst)) {
	$arr_tags[$arr['id']] = $arr['tag'];
}

?>

<form method="post" action="/edit/<?php echo $id_edit_post ?>">
	<div class="form-group">
		<label for="titre">titre</label>
		<input id="titre" class="form-control" name="title" type="text" value="<?php echo (isset($_POST['title']) ? $_POST['title'] : ''); ?>" />
	</div>
	<div class="form-group">
		<label for="date">date</label>
		<input id="date" class="form-control" name="date" type="datetime" value="<?php echo (isset($_POST['date']) ? $_POST['date'] : ''); ?>" />
	</div>
	<div class="form-group">
		<label for="description">description</label>
		<textarea id="description" class="form-control" name="description"><?php echo (isset($_POST['description']) ? $_POST['description'] : ''); ?></textarea>
	</div>
	<div class="form-group">
		<label for="tag">define your tags</label>
		<div class="input-group">
			<input id="tag" class="form-control" name="tag" type="text" value="" />
			<span class="input-group-btn">
				<button type="button" class="plustag btn btn-success">
					<i class="glyphicon glyphicon-plus"></i>
				</button>
			</span>
		</div>
	</div>
	<div class="form-group">
		<label for="tag">Tags ajoutés</label>
		<input id="tags_ids" type="text" name="tags_ids" />
		<input id="tags_names" type="text" name="tags_names" />
		<div id="newtag" style="padding: 20px; border:1px solid #ccc; background: #eee;">
		<?php 
			if (count($arr_tags) > 0) {
				foreach ($arr_tags as $id => $tag) {
					echo '<a href="" class="added_tag" data-id="'.$id.'">'.$tag.'</a> ';
				}
			}
		?>
		</div>
	</div>
	<div class="form-group">
		<label for="tag">Tags retirés (tous)</label>
		<div id="alltags" style="padding: 20px; border:1px solid #eee; background: #f2f2f2;">
		</div>
	</div>

	<label for="category">define your categories</label>
 	
 		<?php 
			if (count($arr_categories) > 0) {
				$i = 0;
				foreach ($arr_categories as $id => $cat) {
					echo '<div class="input-group">';
						echo toSELECT($arr_tree, 0, 'category_parent[]', $id);
						?>
						<span class="input-group-btn">
							<button type="button" class="plus btn btn-success">
								<i class="glyphicon glyphicon-plus"></i>
							</button>
							<button type="button" class="minus btn btn-danger" <?php echo ( $i === 0 ? 'disabled="disabled"' : '' ); ?>>
								<i class="glyphicon glyphicon-minus"></i>
							</button>
						</span>
					</div>
					<?php
					$i++;
				}
			}
			else {
				echo '<div class="input-group">';
					echo toSELECT($arr_tree, 0, 'category_parent[]');
					?>
					<span class="input-group-btn">
						<button type="button" class="plus btn btn-success">
							<i class="glyphicon glyphicon-plus"></i>
						</button>
						<button type="button" class="minus btn btn-danger" disabled="disabled">
							<i class="glyphicon glyphicon-minus"></i>
						</button>
					</span>
				</div>
				<?php
			}
		?>
	<div id="anotherCategory"></div>

	<div class="clearfix"></div>
	<br />
	<button type="submit" name="submit" id="button" value="submit"  class="btn btn-primary">
		submit
	</button>
</form>

<script type="text/javascript">
	$(document).ready(function() {
	    $(document).on('click', '.added_tag', function(event) {
	    	event.preventDefault();
	    	console.log(event);
      		var el = $(event.currentTarget);

			var clone = el.clone();
			clone.removeClass('added_tag').addClass('existing_tag');
			el.remove();

			$('#alltags').append(clone);
			$('#alltags').append(' ');

			$('#tags_ids').val('');
			$.each($('#newtag').find('a'), function(key, a) {
				console.log(a);
				var id = $(a).attr('data-id');
				console.log(id)
				$('#tags_ids').val($('#tags_ids').val()+id+',')
			});
	  	});
	    $(document).on('click', '.existing_tag', function(event) {
	    	event.preventDefault();
	    	var el = $(event.currentTarget);
	    	var id = el.attr('data-id');

	    	if (id > 0) {
				var clone = el.clone();
				clone.removeClass('existing_tag').addClass('added_tag');
				el.remove();

				$('#newtag').append(clone);
				$('#newtag').append(' ');

				$('#tags_ids').val('');
				$.each($('#newtag').find('a'), function(key, a) {
					console.log(a);
					var id = $(a).attr('data-id');
					console.log(id)
					$('#tags_ids').val($('#tags_ids').val()+id+',')
				});
			}
			else {
				el.remove();
				$('#tags_names').val('');
				$.each($('#newtag').find('a'), function(key, a) {
					var id = $(a).attr('data-id');
					if (!id) {
						$('#tags_names').val($('#tags_names').val()+$(a).text()+',')
					}
				});
			}
	    });
	    $(document).on('click', '.plustag', function(event) {
	    	var val = $('#tag').val();
	    	if (val.length > 0) {
			$('#newtag').append('<a href="#" class="existing_tag">'+$('#tag').val()+'</a>');
				$('#newtag').append(' ');
				$('#tag').val('');

				$('#tags_names').val('');
				$.each($('#newtag').find('a'), function(key, a) {
					var id = $(a).attr('data-id');
					if (!id) {
						$('#tags_names').val($('#tags_names').val()+$(a).text()+',')
					}
				});
			}
			else {
				alert('Please insert tag');
			}
		});

  	});
</script>
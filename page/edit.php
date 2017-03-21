<?php
$cnt2 = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
mysqli_query($cnt2, "SET NAMES 'utf8'");

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
 * 8. J'affiche mes categories dans le formulaire
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

	$tags_ids = $_POST['tags_ids'];

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

					// 11. Mettre les tags NAMES dans un tableau de type arrUpdateTagNames[] = name

					$arrUpdateTagNames = explode(',',$tags_names);
					$arrUpdateTagIds = explode(',',$tags_ids);
					if(sizeof($arrUpdateTagNames)  > 1) {
						foreach ($arrUpdateTagNames as $tag) {
							if($tag != '') {
								$query = "INSERT IGNORE INTO tags
									(id,
									tag,
									url,
									status)
									VALUES
									(NULL,
									'$tag',
									'".slugify($tag)."',
									1)";
								$rst = mysqli_query($cnt, $query);
								$query = "SELECT * FROM tags WHERE tag = '{$tag}'";
								if ($rst = mysqli_query($cnt,$query)) {
									while($arr = mysqli_fetch_array($rst)) {
										$recup_tag_id = $arr['id'];
										$query = "INSERT INTO posts_tags
										(post_id,
										tag_id)
										VALUES
										($id_edit_post,
										$recup_tag_id)";
										$rst = mysqli_query($cnt, $query);
									}
								}
							}
						}
					}
					if(sizeof($arrUpdateTagNames)  > 1 && sizeof($arrUpdateTagIds) == 0) {
						$query = "DELETE FROM posts_tags WHERE post_id = $id_edit_post";
						$rst = mysqli_query($cnt, $query);
						foreach ($arrUpdateTagNames as $tag) {
							if($tag != '') {
								$query = "INSERT IGNORE INTO tags
									(id,
									tag,
									url,
									status)
									VALUES
									(NULL,
									'$tag',
									'".slugify($tag)."',
									1)";
								$rst = mysqli_query($cnt, $query);
								$query = "SELECT * FROM tags WHERE tag = '{$tag}'";
								var_dump($query);
								$rst = mysqli_query($cnt,$query);
								while($arr = mysqli_fetch_array($rst)) {
									$recup_tag_id = $arr['id'];
									$query = "INSERT INTO posts_tags
									(post_id,
									tag_id)
									VALUES
									($id_edit_post,
									$recup_tag_id)";
									$rst = mysqli_query($cnt, $query);
								}
							}
						}
					}

					// . Mettre les tags ids dans un tableau de type arrUpdateTagids[] = id

					if (sizeof($arrUpdateTagIds) > 1 && sizeof($arrUpdateTagNames) == 1) {
						$query = "DELETE FROM posts_tags WHERE post_id = $id_edit_post";
						var_dump($query);
						$rst = mysqli_query($cnt, $query);
						;
						foreach ($arrUpdateTagIds as $id) {
							$query = "INSERT INTO posts_tags
								(post_id,
								tag_id)
								VALUES
								($id_edit_post,
								$id)";
							$rst = mysqli_query($cnt, $query);
						}
					}
					if(count($arrUpdateTagNames) == 1 && count($arrUpdateTagIds) == 1) {
						$query = "DELETE FROM posts_tags WHERE post_id = $id_edit_post";
						$rst = mysqli_query($cnt, $query);
					}

					// update categories

					if(isset($_POST['category_parent'])) {
						if($_POST['category_parent'] > 0) {
							$arr_cat = $_POST['category_parent'];
						}
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
						$query = "DELETE FROM posts_cats WHERE post_id = $id_edit_post";
						$rst = mysqli_query($cnt,$query);
						foreach ($arr_cat as $key => $id_cat) {
							if($id_cat != "-1") {
								$query = "INSERT INTO posts_cats
								(post_id,
								cat_id)
								VALUES
								($id_edit_post,
								$id_cat)";
								$rst = mysqli_query($cnt,$query);
							}
						}
					}
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
			<input id="tag" class="form-control" name="tag" type="text" value=""/>
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
						echo toSELECT($arr_tree, 0, 'category_parent[]', $id,$cat);
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
		// click for tags
		$.each($('#newtag').find('a'), function(key, a) {
				console.log(a);
				var id = $(a).attr('data-id');
				$('#tags_ids').val($('#tags_ids').val()+id+',');
			});
	    $(document).on('click', '.added_tag', function(event) {
	    	event.preventDefault();
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
				$('#tags_ids').val($('#tags_ids').val()+id+',');
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
					$('#tags_ids').val($('#tags_ids').val()+id+',')
				});
			}
			else {
				el.remove();
				$('#tags_names').val('');
				$.each($('#newtag').find('a'), function(key, a) {
					var id = $(a).attr('data-id');
					if (!id) {
						$('#tags_names').val($('#tags_names').val()+$(a).text()+',');
					}
				});
			}
	    });
	    $(document).on('click', '.plustag', function(event) {
	    	var val = $('#tag').val().split(',');
	    	for (i = 0; i < val.length; i++) {
		    	if (val.length > 0) {
					$('#newtag').append('<a href="#" class="existing_tag">'+val[i]+'</a>');
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
			}
		});
		// click for categories
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

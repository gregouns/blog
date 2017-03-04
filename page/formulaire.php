<?php
$message = '';

$cat     = '';
if (isset($_POST['cat'])) {
	$cat  = $_POST['cat'];
	$arr_cat = array();
}

$Scat = '';
if (isset($_POST['Scat']))	{
	$Scat  = $_POST['Scat'];
	$arr_Scat = array();
	if ($Scat != '') {
		$arr_Scat = explode(',', $Scat);
	}
}




if ( $_POST ) {
	$_error = false;
	if ( isset($_POST['cat']) && strlen($cat) == 0 ) {
		$_error = true;
		$msgError[] = 'Merci de renseigner une catégorie';
	}
	else {
		$cat   = strip_tags($cat);
		$Scat  = strip_tags($Scat);
		// condition si categorie du premier input exist
		$query_cat_exist = "SELECT * FROM categories Where name = '{$cat}'";
		$rst_cat_exist = mysqli_query($cnt,$query_cat_exist);
		if(mysqli_num_rows($rst_cat_exist) > 0) {
			// recupere l' id de la categorie existante
			$query_Scat_exist = "SELECT * FROM categories Where name = '{$Scat}'";
			$rst_Scat_exist = mysqli_query($cnt,$query_Scat_exist);
			if(mysqli_num_rows($rst_Scat_exist) > 0) {
				// condition si la sous categorie existe deja et je regarde si je ne peux pas l a update sous un nouveau menu
				$query_cat_update = "SELECT * FROM categories Where name = '{$cat}'";
				$rst_cat_update = mysqli_query($cnt,$query_cat_update);
				if ($arr = mysqli_fetch_array($rst_cat_update)) {
					$query_Scat_update = "UPDATE categories SET  id_parent = '{$arr['id']}' WHERE name = '{$Scat}'";
					$rst_Scat_update = mysqli_query($cnt,$query_Scat_update);
					$message = '<div class="alert alert-success">Votre catégorie a bien été inséré</div>';
					echo $message;
					$message = '<div class="alert alert-info">Votre sous catégorie a bien été inséré</div>';
					echo $message;
				}
			}
			if ( isset($Scat) && strlen($Scat) == 0 ) {
				// condition si la personne n a pas choisi de sous menu
				$message = '<div class="alert alert-success">Votre menu a bien été inséré</div>';
				echo $message;
			}
			else {
				$query_Scat_exist = "SELECT * FROM categories Where name = '{$Scat}'";
				$rst_Scat_exist = mysqli_query($cnt,$query_Scat_exist);
				if(mysqli_num_rows($rst_Scat_exist) == 0) {
					// insertion du nouveau sous menu
					$query_cat_recup = "SELECT id FROM categories Where name = '{$cat}'";
					$rst__cat_recup = mysqli_query($cnt, $query_cat_recup);
					while ($arr = mysqli_fetch_array($rst__cat_recup)) {
						$query_Scat_insert = "INSERT INTO categories (id,id_parent,name,url,status)
							VALUES (NULL,'{$arr['id']}','{$Scat}','".slugify($Scat)."',1)";
						$rst_Scat = mysqli_query($cnt, $query_Scat_insert);
						$message = '<div class="alert alert-info">Votre sous menu a bien été inséré</div>';
						echo $message;
					}
				}
			}
		}
		// condition 1er menu n' existe pas
		else {
			// insertion du menu
			$query_cat_insert = "INSERT INTO `categories` (`id`,`id_parent`,`name`,`url`,`status`) VALUES ( NULL,0,'{$_POST['cat']}','".slugify($cat)."',1)";
			$rst_cat_insert = mysqli_query($cnt, $query_cat_insert);
			if ( isset($Scat) && strlen($Scat) == 0 ) {
				$message = '<div class="alert alert-success">Votre menu a bien été inséré</div>';
				echo $message;
			}
			else {
				// condition si le sous menu n' existe pas
				$query_Scat_exist = "SELECT * FROM categories Where name = '{$Scat}'";
				$rst_Scat_exist = mysqli_query($cnt,$query_Scat_exist);
				if(mysqli_num_rows($rst_Scat_exist) > 0) {
					$message = '<div class="alert-warning">votre menu appartient déjà a un menu</div>';
					echo $message;
				}
				else {
					// recherche id du parent crée
					$query_cat_select = "SELECT id FROM categories Where name ='{$cat}'";
					$rst_cat_select = mysqli_query($cnt,$query_cat_select);
					// creation d un nouveau sous menu
					while ($arr = mysqli_fetch_array($rst_cat_select)) {
					$query_Scat_insert = "INSERT INTO categories (id,id_parent,name,url,status)
						VALUES (NULL,'{$arr['id']}','{$Scat}','".slugify($Scat)."',1)";
					$rst_Scat = mysqli_query($cnt, $query_Scat_insert);
					$message = '<div class="alert alert-info">votre sous menu a bien été inséré</div>';
					echo $message;
					}
				}
			}
		}
	}
}


function buildTree ( $cat_begin = 0 ) {
  global $cnt;

  if ($cat_begin > 0) {
    $query = "SELECT * FROM categories WHERE id_parent = '{$cat_begin}' ORDER BY name ASC";

  }
  else {
    $query = "SELECT * FROM categories WHERE id_parent = 0 ORDER BY name ASC";
  }
  $rst = mysqli_query($cnt,$query);

  if (mysqli_num_rows($rst) > 0) {
    while ($arr = mysqli_fetch_array($rst)) {
      $cat[slugify($arr['name'])] = array(
        'id'        => $arr['id'],
        'name'      => $arr['name'],
        'children' => buildTree($arr['id']),
      );
    }
  }
  else {
    return array();
  }

  return $cat;
}

$arr_tree = buildTree();

print_r($arr_tree);
echo '<hr />';
echo toUL($arr_tree);
echo '<hr />';
?>

<fieldset>
  <legend>duke form <small>(n'ajoute pas encore les categories juste une affichage)</small></legend>
  <form>
    <div class="form-group">
      <label>Catgeory name</label>
      <input type="text" class="form-control" name="category" value="" placeholder="Enter your category" />
    </div>
    <div class="form-group">
      <label>Category parente</label>
      <?php echo toSELECT($arr_tree); ?>
      <p class="help text-warning">
        <small>Laissez vide si vous souhaitez créer une catégorie parente</small>
      </p>
    </div>
    <button class="btn btn-primary">
      envoyer
    </button>
  </form>
</fieldset>

<br /><br />

<fieldset>
  <legend>greg form</legend>
  <form method="post" action="/formulaire">
  	<div class="form-group">
  		<label for="cat">catégorie</label>
  		<input id="cat" class="form-control" name="cat" type="text" placeholder = "ajoutez une catégorie" value="<?php if (isset($_POST['cat'])) {echo $_POST['cat'];}?>" />
  	</div>
  	<div class="form-group">
  		<label for="Scat">sous catégorie</label>
  		<input id="Scat" class="form-control" name="Scat" type="text" placeholder = "ajoutez une sous catégorie" value="<?php if (isset($_POST['Scat'])) {echo $_POST['Scat'];}?>" />
  	</div>
  	<button>envoyer</button>
  </form>
</fieldset>
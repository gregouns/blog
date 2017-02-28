<?php
$message = '';

$cat     = '';
if (isset($_POST['cat'])) {
	$cat  = $_POST['cat'];
	$arr_cat = array();
	if ($cat != '') {
		$arr_cat = explode(',', $cat);
	}
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
		// condition categorie exist
		$query_cat_exist = "SELECT * FROM categories Where name = '{$cat}'";
		$rst_cat_exist = mysqli_query($cnt,$query_cat_exist);
		if(mysqli_num_rows($rst_cat_exist) > 0) {
			$query_cat_recup = "SELECT cat.id AS cid FROM categories Where name = '{$name}'";
			$rst_cat_recup = mysqli_query($cnt, $query_cat_recup);
			foreach ($arr_Scat as $key => $Scat) {
				while($arr = mysqli_fetch_array($rst_cat_recup)) {
					$query_Scat_insert = "INSERT INTO categories (`id`, `id_parent`, `name`, `status`) 
						VALUES (NULL, '{$arr['id']}' , `{$Scat}` , 1)";
						echo $query_Scat_insert;
					$rstScat = mysqli_query($cnt, $query_Scat_insert);
				}
			}	
		}
		else {
			$query_cat_insert = "INSERT INTO categories (`id`, `id_parent`, `name`, `status`) VALUES ( NULL, NULL , `{$_POST['cat']}` , 1)";
			echo $query_cat_insert;
			$rst_cat_insert = mysqli_query($cnt, $query_cat_insert);
			$id = mysqli_insert_id($cnt);
			foreach ($arr_Scat as $key => $Scat) {
				$query_Scat_insert = "INSERT INTO categories (`id`, `id_parent`, `name`, `status`) 
					VALUES (NULL, '{$id}' , `{$Scat}` , 1)";
				$rstScat = mysqli_query($cnt, $query_Scat_insert);
			}
			$message = '<div class="alert alert-success">Votre post a bien été inséré</div>';
		}		
	}
}

?>
<form method="post" action="/formulaire">
	<div class="form-group">
		<label for="cat">catégorie</label>
		<input id="cat" class="form-control" name="cat" type="text" value="<?php if (isset($_POST['cat'])) {echo $_POST['cat'];}?>" />
	</div>
	<div class="form-group">
		<label for="Scat">sous catégorie</label>
		<input id="Scat" class="form-control" name="Scat" type="text" value="<?php if (isset($_POST['Scat'])) {echo $_POST['Scat'];}?>" />
	</div>
	<button>envoyer</button>
</form>
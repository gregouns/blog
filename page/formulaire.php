<?php
$message = [];
$messageErr = [];
$cat = $_POST && $_POST['category'] ? $_POST['category'] : null;
$cat_parent = $_POST && $_POST['category_parent'] ? $_POST['category_parent'] : null;
if ($_POST) {
  $_error = 0;
	if (!empty($cat)) {
		$cat = addslashes(strip_tags(trim($cat)));
		$cat_url = slugify($cat);
	}
  else {
    $_error = 1;
    $messageErr[] = 'La catégorie existe déjà';
  }
  if ($_error == 0) {
    // On check la categorie parente
    $parent_id = (($cat_parent > 0) ? $cat_parent : 0);
    if (isCategoryExists($cat, $parent_id) == false) {
      // On insére donc la catégorie d'abord
      $query = "INSERT INTO categories (id, id_parent, name, url, status) VALUES (
        NULL,
        '{$parent_id}',
        '{$cat}',
        '{$cat_url}',
        '1'
      )";
      if ($rst = mysqli_query($cnt, $query)) {
        $inserted_id = mysqli_insert_id($cnt);
        $message[] = 'La catégorie a bien été insérée';
      }
      else {
        $messageErr[] = 'Une erreur s\'est produite';
      }
    }
    else {
      $messageErr[] = 'La catégorie existe déjà';
    }
  }
}
?>

<?php
$arr_tree = buildTree();
?>

<fieldset>
  <legend>Ajouter une catégorie</small></legend>
  <form method="post" action="/formulaire">
    <?php
    if (sizeof($message) > 0) {
      echo '<div class="alert alert-success">
        '.implode(',', $message).'
      </div>';
    }
    if (sizeof($messageErr) > 0) {
      echo '<div class="alert alert-danger">
        '.implode(',', $messageErr).'
      </div>';
    }
    ?>
    <div class="form-group">
      <label>Category name</label>
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
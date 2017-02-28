




<form method="post" action="/post">
	<div class="form-group">
		<label for="cat">catégorie</label>
		<input id="cat" class="form-control" name="cat" type="text" value="<?php if (isset($_POST['cat'])) {echo $_POST['cat'];}?>" />
	</div>
	<div class="form-group">
		<label for="SCat">sous catégorie</label>
		<input id="SCat" class="form-control" name="SCat" type="text" value="<?php if (isset($_POST['SCat'])) {echo $_POST['SCat'];}?>" />
	</div>
	<button>envoyer</button>
</form>
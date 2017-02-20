<form method="post" action="/post">
	<div class="form-group">
		<label for="titre">titre</label>
		<input id="titre" class="form-control" name="titre" type="text" value="<?php if (isset($_POST['titre'])) {echo $_POST['titre'];}?>" />
	</div>
	<div class="form-group">
		<label for="date">date</label>
		<input id="date" class="form-control" name="date" type="text" value="<?php if (isset($_POST['date'])) {echo $_POST['lastname'];}?>" />
	</div>
	<div class="form-group">
		<label for="description">description</label>
		<textarea id="description" class="form-control" name="description" value="<?php $_POST['description']?>">
		</textarea>
	</div>
	<button>envoyer</button>
</form>
<form method="post" action="/post">
	<div class="form-group">
		<label for="titre">titre</label>
		<input id="titre" class="form-control" name="titre" type="text" value="<?php if (isset($_POST['titre'])) {echo $_POST['titre'];}?>" />
	</div>
	<div class="form-group">
		<label for="lastname">date</label>
		<input id="lastname" class="form-control" name="lastname" type="text" value="<?php if (isset($_POST['lastname'])) {echo $_POST['lastname'];}?>" />
	</div>
	<div class="form-group">
		<label for="message">description</label>
		<textarea id="message" class="form-control" name="message" value="<?php $_POST['message']?>">
		</textarea>
	</div>
	<button>envoyer</button>
</form>
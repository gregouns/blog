<?php

$id_edit_post = $_GET['edit'];

if (isset($_POST['submit'])) {
	// update title, description and date
	$title = $_POST['title'];
	$title = addslashes(strip_tags(trim($title)));
	$date  = $_POST['date'];
	$description = $_POST['description'];
	$description = addslashes(strip_tags(trim($description)));
	$query = "UPDATE posts
		SET
		`title` = '{$title}',
		`date`  = '{$date}',
		`description` = '{$description}',
		`url` = '".slugify($title)."'
		";
	mysqli_real_escape_string($cnt, $title);
	$rst = mysqli_query($cnt,$query);
	// update tags
 	$tag = $_POST['tag'];
	foreach ($tag as $val) {
		$query = "UPDATE tags
		SET
		`tag` = '{$val}',
		`url` = '".slugify($val)."'
		";
		$rst = mysqli_query($cnt,$query);

	}
	if(isset($_POST['category_parent'])) {
		if($_POST['category_parent'] > 0){
			$arr_cat = $_POST['category_parent'];
		}
	}
	foreach ($arr_cat as $key => $cat_id) {
		$query = "UPDATE posts_cats
		SET
		`cat_id` = '{$cat_id}'
		WHERE post_id = $id_edit_post";
		$rst = mysqli_query($cnt,$query);
	}
}

if (isset($_POST)) {
	// select title description and date
	$query = "SELECT * FROM posts WHERE id = '{$id_edit_post}'";
	$rst = mysqli_query($cnt,$query);
	while ($arr = mysqli_fetch_array($rst)) {
	$arr_post = array(
		'titre' => array(
			'name' => 'title',
			'type' => 'text',
			'value' => $arr['title']
			),
		'date' => array(
			'name' => 'date',
			'type' => 'datetime',
			'value' => $arr['date']
			)
		);
	$description = $arr['description'];
	}
	// select tag
	$query = "SELECT *,t.id AS tid FROM tags AS t,posts_tags AS pt,posts AS p  WHERE p.id = '{$id_edit_post}' AND pt.post_id = p.id AND pt.tag_id = t.id";
	$rst = mysqli_query($cnt,$query);
	while ($arr = mysqli_fetch_assoc($rst)) {
		$tag[] = $arr['tag'];
	}

	// select categories
	$query = "SELECT *,pc.post_id AS pcid, p.id AS pid, pc.cat_id AS pccid, c.id AS cid FROM categories AS c, posts_cats AS pc, posts AS p WHERE pc.post_id = '{$id_edit_post}' AND pc.cat_id = c.id";
	var_dump($query);
	$rst = mysqli_query($cnt,$query);
	while ($arr = mysqli_fetch_array($rst)) {
		$name[] = $arr['name'];
		$cat_id = $arr['pccid'];
		var_dump($cat_id);
		$arr_tree = buildTree();
	}
}
function generate($array) {
	$html = '';
	foreach ($array as $val) {
		$html .= '<div class = "form-group">';
		$html .= '<label>' . $val['name'] . '</label><br/>';
		$html .= '<input class="form-control" name = "'.$val['name'].'" type = "'.$val['type'].'" value = "'.$val['value'].'" /><br/></div>';
	}

	return $html;
}


/* ------------------------------------------------------- */
/* duke */
/* ------------------------------------------------------- */
?>
<script type="text/javascript">
  function findElementByText(text){
    // console.log($("div#newtag:contains('"+text+"')"));
      var jSpot=$("div#newtag:contains('"+text+"')")
        .filter(function(){ return $(this).children().length === 0;})
        // .parent();  // because you asked the parent of that element

      return jSpot;
   }
  $(document).ready(function() {
    // Version simple quasiment dupliquée ! Peut etre améliroée !
    // Mode sale !! mais rapide !
    $(document).on('click', '.existing_tag', function(e) {
      e.preventDefault();
      var el = $(e.currentTarget);
      var el2 = el.clone();
      el2.removeClass('existing_tag').addClass('added_tag');
      el.remove();

      $('#newtag').append(el2);
      $('#newtag').append(' ');
    });
    $(document).on('click', '.added_tag', function(e) {
      var el = $(e.currentTarget);
      var el2 = el.clone();
      el2.removeClass('added_tag').addClass('existing_tag');
      el.remove();

      $('#alltag').append(el2);
      $('#alltag').append(' ');
    });
    $(document).on('click', '.plustag', function(e) {
      $('#newtag').append('<a href="#" class="added_tag">'+$('#mynewtag').val()+'</a>');
      $('#newtag').append(' ');
      $('#mynewtag').val('');
    });
  });
</script>
<?php


$query = "SELECT * FROM tags";
$rst = mysqli_query($cnt,$query);
echo '<hr /><h2>DUKE -- tag</h2><hr /><u>Ajouter des tags parmis les tags existants :</u>';
echo '<div id="alltag">';
while ($arr = mysqli_fetch_assoc($rst)) {
  echo '<a href="#" class="existing_tag" data-id="'.$arr['id'].'">'.$arr['tag'].'</a> ';
}
echo '</div>';
echo '<u>ou ajouter de nouveaux tags :</u>';
echo '<div class="input-group">
<span class="input-group-btn"><input type="text" id="mynewtag" placeholder="votre tag" class="form-control" />
      <button type="button" class="plustag btn btn-success">
        <i class="glyphicon glyphicon-plus"></i>
      </button></span></div>';
echo '<u>Vos tags sélectionnés:</u>';
echo '<div id="newtag" style="padding: 20px; border:1px solid #ccc; background: #eee;"></div>';
echo '<hr /><br /><br />';
/* ------------------------------------------------------- */
/* end */
/* ------------------------------------------------------- */


echo '<form method="post" action="/edit/'.$id_edit_post.'">';
echo generate($arr_post);
echo '<div class = "form-group"><label>description</label><br/><textarea class="form-control" name = "description" type = "text">' .$description . ' </textarea><br/></div>';
if(isset($tag)){
	$tag = implode(",", $tag);
	echo '<div class = "form-group"><label>tag</label><br/><input class="form-control" name = "tag[]" type = "text" value = "'.$tag.'" /><br/></div>';
}
else {
	echo '<div class = "form-group"><label>tag</label><br/><input class="form-control" name = "tag[]" type = "text" value = "" /><br/></div>';
}
if(isset($name)){
 	foreach ($name as $key => $value) {
		echo '<div class="input-group">
				<label for="category">define your categories</label>';

		echo toSELECT($arr_tree, 0, 'category_parent[]',$value);
		echo '<span class="input-group-btn">
			<button type="button" class="plus btn btn-success">
				<i class="glyphicon glyphicon-plus"></i>
			</button>
			<button type="button" class="minus btn btn-danger">
				<i class="glyphicon glyphicon-minus"></i>
			</button>
		</span>
		</div>
		<div id="anotherCategory"></div>

		<div class="clearfix"></div>
		<br />';
	}
}
else {
	echo '<div class="input-group">
				<label for="category">define your categories</label>';
		$arr_tree = buildTree();
		echo toSELECT($arr_tree, 0, 'category_parent[]');
		echo '<span class="input-group-btn">
			<button type="button" class="plus btn btn-success">
				<i class="glyphicon glyphicon-plus"></i>
			</button>
			<button type="button" class="minus btn btn-danger">
				<i class="glyphicon glyphicon-minus"></i>
			</button>
		</span>
		</div>
		<div id="anotherCategory"></div>

		<div class="clearfix"></div>
		<br />';
}
echo '<button type="submit" name="submit" id="button" value="submit"  class="btn btn-primary">
		submit
	</button>';
echo '</form>';
echo `<script type="text/javascript">
	$(document).ready(function() {
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
</script>`;



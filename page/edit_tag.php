<?php
$id_edit_post = $_GET['edit_tag'];

if (isset($_POST['submit'])) {
	$tags_names = $_POST['tags_names'];
	$tags_names = cleaner($tags_names);
	$tags_ids = $_POST['tags_ids'];


	$arrUpdateTagNames = explode(',',$tags_names);
	if(sizeof($arrUpdateTagNames)  > 1) {
		var_dump($arrUpdateTagNames);
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

	$arrUpdateTagIds = explode(',',$tags_ids);
	if (sizeof($arrUpdateTagIds) > 1) {
		$query = "DELETE FROM posts_tags WHERE post_id = $id_edit_post";
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
	else {
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
}

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
<form method="post" action="/edit_tag/<?php echo $id_edit_post ?>">
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
		<input id="tags_ids" type="hidden" name="tags_ids" />
		<input id="tags_names" type="hidden" name="tags_names" />
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
	<button type="submit" name="submit" id="button" value="submit"  class="btn btn-primary">
		submit
	</button>
</form>

<script type="text/javascript">
	$(document).ready(function() {
		// click for tags
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

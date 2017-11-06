<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/e-commerce/core/init.php';
if(!is_logged_in())
{
	login_error_redirect();
}
include 'includes/head.php';
include 'includes/navigation.php';

$categories_parent = "SELECT * FROM categories WHERE parent = 0";
$categories_parent = $db->query($categories_parent);

$errors = array();

//delete Category
if(isset($_GET['delete']) && !empty($_GET['delete'])) {
	$delete_id = (int)$_GET['delete'];
	$delete_id = sanitize($delete_id);

	$check_orfan = "SELECT * FROM categories WHERE id = '$delete_id'";
	$check_orfan = $db->query($check_orfan);
	$check_orfan = mysql_fetch_assoc($check_orfan);
	if($check_orfan['parent'] == 0) {
		$delete_orfan_cat = "DELETE FROM categories WHERE parent = '$delete_id'";
		$db->query($delete_orfan_cat);
	}

	$delete_cat_sql = "DELETE FROM categories WHERE id = '$delete_id'";
	$db->query($delete_cat_sql);
	header('Location: categories.php');
}

//edit category
if(isset($_GET['edit']) && !empty($_GET['edit'])) {

	$edit_id = (int)$_GET['edit'];
	$edit_id = sanitize($edit_id);

	$edit_sql = "SELECT * FROM categories WHERE id = '$edit_id'";
	$edit_result = $db->query($edit_sql);
	$category_edit = mysqli_fetch_assoc($edit_result);

}

$category = "";
$post_parent = "";
//Add category
if(isset($_POST) && !empty($_POST)) {

	$post_parent = sanitize($_POST['parent']);
	$category = sanitize($_POST['category']);
	$sql_add_cat = "SELECT * FROM categories WHERE category = '$category' AND parent = '$post_parent'";
	if(isset($_GET['edit'])) {
		$id = $category_edit['id'];
		$sql_add_cat = "SELECT * FROM categories 	WHERE category = '$category' 
													AND parent = '$post_parent'
													AND id != '$id'";
	}
	$sql_add_cat = $db->query($sql_add_cat);
	$count = mysqli_num_rows($sql_add_cat);

	//if category blank
	if($category == "") {
		$errors[] .= "The category cannot be left blank.";
	}

	//if already exists in DB
	if($count > 0) {
		$errors[] .= $category." category already exists. Please choose another one.";
	}

	//display errors or update DB
	if(!empty($errors)) {
		//display errors
		$display = display_errors($errors); ?>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#errors").html('<?=$display; ?>');
			});
		</script>
<?php } else {
		//no errors, insert into DB
		$add_cat_success = "INSERT INTO categories (category, parent) VALUES ('$category', '$post_parent')";
		if(isset($_GET['edit'])) {
			$add_cat_success = "UPDATE categories 
								SET category = '$category', parent = '$post_parent' 
								WHERE id = '$edit_id'";
		}
		$db->query($add_cat_success);
		header('Location: categories.php');
	}
}

$category_value = "";
$parent_value = 0;
if(isset($_GET['edit'])) {
	$category_value = $category_edit['category'];
	$parent_value = $category_edit['parent'];
} else {
	if(isset($_POST)) {
		$category_value = $category;
		$parent_value = $post_parent;
	}
}
?>
<h2 class="text-center">Categories</h2><hr>
<div class="container">
	<div class="row">
		<!-- add Category form -->
		<div class="col-md-6">
			<form class="form" action="categories.php<?=((isset($_GET['edit']))?'?edit='.$edit_id.'':'');?>" method="post">
			<legend><?=((isset($_GET['edit']))? 'Edit ':'Add a '); ?>Category</legend>
			<div id="errors"></div>
				<div class="form-group">
					<label for="parent">Parent :</label>
					<select class="form-control" name="parent" id="parent">
						<option value="0"<?=(($parent_value == 0)?'selected="selected"':'');?>>Parent</option>
						<?php while($parent = mysqli_fetch_assoc($categories_parent)) { ?>
							<option value="<?=$parent['id']; ?>"
									<?=(($parent_value == $parent['id'])?'selected="selected"':'');?>
							>	<?=$parent['category']; ?>
							
							</option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group">
					<label for="category">Category</label>
					<input type="text" class="form-control" id="category" name="category"
					 value="<?=$category_value; ?>">
				</div>
				<div class="form-group">
					<input type="submit" value="<?=((isset($_GET['edit']))? 'Edit ':'Add '); ?> Category" class="btn btn-success">
					<?php if(isset($_GET['edit'])) { ?>
						<a href="categories.php" class="btn btn-default">Cancel</a>
					<?php } ?>
				</div>
			</form>
		</div>
		<!-- CATEGORIES table -->
		<div class="col-md-6">
			<table class="table table-bordered">
				<thead>
					<th>Category</th>	<th>Parent</th>		<th></th>
				</thead>
				<tbody>
				<?php 
				$categories_parent = "SELECT * FROM categories WHERE parent = 0";
				$categories_parent = $db->query($categories_parent);
				while($parent = mysqli_fetch_assoc($categories_parent)) { 
	
						$parent_id = (int)$parent['id'];
						$categories_child = "SELECT * FROM categories WHERE parent ='$parent_id'";
						$categories_child = $db->query($categories_child);
				?>
					<tr class="bg-primary">
						<td><?=$parent['category']; ?></td>
						<td>Parent</td>
						<td>
							<a href="categories.php?edit=<?=$parent['id']; ?>" class="btn btn-xs btn-default">
								<span class="glyphicon glyphicon-pencil"></span>
							</a>
							<a href="categories.php?delete=<?=$parent['id']; ?>" class="btn btn-xs btn-default">
								<span class="glyphicon glyphicon-remove-sign"></span>
							</a>
						</td>
					</tr>
					<?php while($child = mysqli_fetch_assoc($categories_child)) { ?>
							<tr class="bg-info">
						<td><?=$child['category']; ?></td>
						<td><?=$parent['category']; ?></td>
						<td>
							<a href="categories.php?edit=<?=$child['id']; ?>" class="btn btn-xs btn-default">
								<span class="glyphicon glyphicon-pencil"></span>
							</a>
							<a href="categories.php?delete=<?=$child['id']; ?>" class="btn btn-xs btn-default">
								<span class="glyphicon glyphicon-remove-sign"></span>
							</a>
						</td>
					</tr>
					<?php } ?>
				<?php } ?>
				</tbody>
			</table>
		</div>
	
	</div>
</div>
<?php

include 'includes/footer.php';

?>
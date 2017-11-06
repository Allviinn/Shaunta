<?php
require_once "../core/init.php";
if(!is_logged_in())
{
	login_error_redirect();
}
include "includes/head.php";
include "includes/navigation.php";

$brands_query = "SELECT * FROM brand ORDER BY brand";

$brands_query = $db->query($brands_query);

$errors = array();

//edit Brand 
if(isset($_GET['edit']) && !empty($_GET['edit'])) {

	$edit_id = (int)$_GET['edit'];
	$edit_id = sanitize($edit_id);

	$edit_brand = "SELECT * FROM brand WHERE id = '$edit_id'";
	$edit_brand = $db->query($edit_brand);
	$edit_brand = mysqli_fetch_assoc($edit_brand);
}


//Delete Brand
if(isset($_GET['delete']) && !empty($_GET['delete'])) {

	$delete_id = (int)$_GET['delete'];
	$delete_id = sanitize($delete_id);

	$delete_brand = "DELETE FROM brand WHERE id = '$delete_id'";
	$db->query($delete_brand);
	header('Location: brands.php');

}



//if add form is submit
if(isset($_POST['add_submit'])) {
	//check if brand is blank
	if(empty($_POST['brand']) && $_POST['brand'] == '') {
		$errors[] .= 'Yo must enter a Brand';
	}

	//check if Brand exist in database
	$brand = sanitize($_POST['brand']);

	$existing_brands = "SELECT * FROM brand WHERE brand='$brand'";
	if(isset($_GET['edit'])) {
		$existing_brands = "SELECT * FROM brand WHERE brand = '$brand' AND != '$edit_id'";
	}
	$result = $db->query($existing_brands);
	$count = mysqli_num_rows($result);
	
	if($count > 0) {
		$errors[] .= $brand." already exists. Please choose another Brand";
	}

	//dispaly errors
	if(!empty($errors)) {
		echo display_errors($errors);
	} else {
		//Add brand to Database
		$add_brand_query = "INSERT INTO brand (brand) VALUES ('$brand')";
		if(isset($_GET['edit'])) {
			$add_brand_query = "UPDATE brand SET brand = '$brand' WHERE id = '$edit_id'";
		}
		$db->query($add_brand_query);
		header('Location: brands.php');
	}
}
?>
<h2 class="text-center">Brands</h2><hr>

<!-- FORMULAIRE BRAND -->
<div class="text-center">
	<form class="form-inline" action="brands.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:''); ?>" method="post">
		<div class="form-group">
		<?php 
			$brand_value = '';

			if(isset($_GET['edit'])){
				$brand_value = $edit_brand['brand'];
			} else {
				if(isset($_POST['brand'])) {
					$brand_value = sanitize($_POST['brand']);
				}
			}
			?>
			<label for="brand"><?=((isset($_GET['edit']))?'Edit ':'Add a ') ; ?>Brand :</label>
			<input 	type="text" 
					name="brand" 
					id="brand" 
					class="form-control" 
					value="<?=$brand_value ?>"
			>
			<?php if(isset($_GET['edit'])) { ?>
				<a href="brands.php" class="btn btn-default">Cancel</a>
			<?php } ?>
			<input type="submit" name="add_submit" value="<?=((isset($_GET['edit']))?'Edit ':'Add ') ; ?> Brand" class="btn btn-success">
		</div>
	</form>
</div><hr>

<table class="table table-bordered table-striped table-auto table-condensed">
	<thead>
		<th></th>	<th>Brands</th>	<th></th>
	</thead>
	<tbody>
		<?php while($brand_results = mysqli_fetch_assoc($brands_query)) { ?>
			<tr>
				<td>
					<a href="brands.php?edit=<?=$brand_results['id']; ?>" class="btn btn-xs btn-default">
						<span class="glyphicon glyphicon-pencil"></span>
					</a>
				</td>
				<td><?=$brand_results['brand']; ?></td>
				<td>
					<a href="brands.php?delete=<?=$brand_results['id']; ?>" class="btn btn-xs btn-default">
						<span class="glyphicon glyphicon-remove-sign"></span>
					</a>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>

<?php
include "includes/footer.php";
?>
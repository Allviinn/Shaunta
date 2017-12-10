<?php
require_once "../core/init.php";
if(!is_logged_in())
{
	login_error_redirect();
}
include "includes/head.php";
include "includes/navigation.php";

//rednu du template
$loader = new Twig_Loader_Filesystem('templates_admin');
$twig = new Twig_Environment($loader, [
		'cache' => false
	]);

$twig->addExtension(new MonExtension());


$brands_query = $db->query("SELECT * FROM brand ORDER BY brand");
$brand_results = [];
while($b = mysqli_fetch_assoc($brands_query))
{
	$brand_results[] = $b;
}

$errors = array();

$edit_id = "";
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

$get_edit = false;
if(isset($_GET['edit']))
{
	$get_edit = true;
}

$brand_value = '';
if(isset($_GET['edit'])){
	$brand_value = $edit_brand['brand'];
} else {
	if(isset($_POST['brand'])) {
		$brand_value = sanitize($_POST['brand']);
	}
}

echo $twig->render('brands.twig', [
		'get_edit' => $get_edit,
		'brand_value' => $brand_value,
		'edit_id' => $edit_id,
		'brand_results' => $brand_results
	]);

include "includes/footer.php";
?>
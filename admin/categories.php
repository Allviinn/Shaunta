<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/e-commerce/core/init.php';
if(!is_logged_in())
{
	login_error_redirect();
}
include 'includes/head.php';
include 'includes/navigation.php';

//rednu du template
$loader = new Twig_Loader_Filesystem('templates_admin');
$twig = new Twig_Environment($loader, [
		'cache' => false
	]);


$twig->addExtension(new MonExtension());

$parent_array = [];
$categories_parent = "SELECT * FROM categories WHERE parent = 0";
$categories_parent = $db->query($categories_parent);
while($parent = mysqli_fetch_assoc($categories_parent))
{
	$parrent_array[] = $parent;
}



$errors = array();

$delete_id = '';
//delete Category
if(isset($_GET['delete']) && !empty($_GET['delete'])) {
	$delete_id = (int)$_GET['delete'];
	$delete_id = sanitize($delete_id);

	$check_orfan = "SELECT * FROM categories WHERE id = '$delete_id'";
	$check_orfan = $db->query($check_orfan);
	$check_orfan = mysqli_fetch_assoc($check_orfan);
	if($check_orfan['parent'] == 0) {
		$delete_orfan_cat = "DELETE FROM categories WHERE parent = '$delete_id'";
		$db->query($delete_orfan_cat);
	}

	$delete_cat_sql = "DELETE FROM categories WHERE id = '$delete_id'";
	$db->query($delete_cat_sql);
	header('Location: categories.php');
}



$edit_id = '';
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




$categories_parent = $db->query("SELECT * FROM categories WHERE parent = 0");

$parent_cat_array = [];
$child_cat_array = [];

while($parent = mysqli_fetch_assoc($categories_parent)) 
{ 

		$parent_cat_array[] = $parent;

		$parent_id = (int)$parent['id'];
		$categories_child = $db->query("SELECT * FROM categories WHERE parent ='$parent_id'");

	while($child = mysqli_fetch_assoc($categories_child)) 
	{ 
		$child_cat_array[] = $child;
	} 
}


echo $twig->render('categories.twig', [
		'parent_array' => $parrent_array,
		'delete_id' => $delete_id,
		'edit_id' => $edit_id,
		'category_value' => $category_value,
		'parent_value' => $parent_value,
		'parent_cat_array' => $parent_cat_array,
		'child_cat_array' => $child_cat_array
	]);	 



include 'includes/footer.php';

?>
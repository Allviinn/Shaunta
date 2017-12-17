<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/shaunta/core/init.php';
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

/********************************************************************************************
**************************** DESUPPRESSION PRODUIT   ****************************************
********************************************************************************************/
if(isset($_GET['restore']))
{
	$restore_id = sanitize($_GET['restore']);
	$db->query("UPDATE products SET deleted = '0' WHERE id = '$restore_id'");
	//header('Location: products.php');
}

$archivedProducts = $db->query("SELECT * FROM products WHERE deleted = '1'");
$archived_array = [];
while($archProduct = mysqli_fetch_assoc($archivedProducts)) 
{ 
	$archived_array[] = $archProduct;
} 

$count_array = count($archived_array);

//echo $count_array;

for($i = 0; $i < $count_array; $i++)
{
	$category_id = $archived_array[$i]['categories'];
	$category_id = (int)$category_id;
	$category = get_category($category_id);
	$category = $category['parent'].' ~ '. $category['child'];
	$archived_array[$i]['category'] = $category;
}

echo $twig->render('archived.twig', [
		'archived_array' => $archived_array,
	]);	

include 'includes/footer.php';
?>
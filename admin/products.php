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
**************************** SUPPRESSION PRODUIT   ******************************************
********************************************************************************************/
if(isset($_GET['delete']))
{
	$id = sanitize($_GET['delete']);
	$db->query("UPDATE products SET deleted = '1' WHERE id = '$id'");
	header('Location: products.php');
}



$dbpath = '';
/********************************************************************************************
****************************    AJOUT PRODUIT   *********************************************
********************************************************************************************/
$get_add = false;
$get_edit = false;
$edit_id = '';
$title = '';
$brand = '';
$brand_array = [];
$parent = '';
$parent_array = [];
$price = '';
$list_price = '';
$sizes = '';
$saved_image = '';
$image_array = [];
$description = '';
$sArray = [];
$qArray = [];
$tArray = [];
$product_array = [];

if(isset($_GET['edit']))
{
	$get_edit = true;
}
if(isset($_GET['add']))
{
	$get_add = true;
}

if(isset($_GET['add']) || isset($_GET['edit'])) 
{
	$brand_query = $db->query("SELECT * FROM brand ORDER BY brand");
	$parent_query = $db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");
	$title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
	$brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):'');
	$parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):'');
	$category = ((isset($_POST['child']) && !empty($_POST['child']))?sanitize($_POST['child']):'');
	$price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):'');
	$list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):'');
	$description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):'');
	$sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']):'');
	$saved_image = "";
	

	if(isset($_GET['edit']))
	{
		$edit_id = (int)$_GET['edit'];
		$productResults = $db->query("SELECT * FROM products WHERE id = '$edit_id'");
		$product = mysqli_fetch_assoc($productResults);

		if(isset($_GET['delete_image']))
		{
			$imgi = (int)$_GET['imgi'] - 1;
			$images = explode(',', $product['image']);
			$img_url = $_SERVER['DOCUMENT_ROOT'].$images[$imgi];
			unlink($img_url);
			unset($images[$imgi]);
			$imageString = implode(',', $images);
			$db->query("UPDATE products SET image = '$imageString' WHERE id = '$edit_id'");
			header('Location: products.php?edit='.$edit_id);
		}

		$category = ((isset($_POST['child']) && $_POST['child'] != "")?sanitize($_POST['child']):$product['categories']);
		$title = ((isset($_POST['title']) && !empty($_POST['title']))?sanitize($_POST['title']):$product['title']);
		$brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):$product['brand']);
		$parentQ = $db->query("SELECT * FROM categories WHERE id = '$category'");
		$parentResult = mysqli_fetch_assoc($parentQ);
		$parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):$parentResult['parent']);
		$price = ((isset($_POST['price']) && !empty($_POST['price']))?sanitize($_POST['price']):$product['price']);
		$list_price = ((isset($_POST['list_price']) && !empty($_POST['list_price']))?sanitize($_POST['list_price']):$product['list_price']);
		$description = ((isset($_POST['description']) && !empty($_POST['description']))?sanitize($_POST['description']):$product['description']);
		$sizes = ((isset($_POST['sizes']) && !empty($_POST['sizes']))?sanitize($_POST['sizes']):$product['sizes']);
		$saved_image = (($product['image'] != "")?$product['image']:"");
		$dbpath = $saved_image;
	}

	if(!empty($sizes)) 
	{
		$sizeString = sanitize($sizes);
		
		//$sizeString = rtrim($sizeString,',');  j'enleve la virgule a la fin de la chaine en JavaScript dans lefooter dans la fonction updateSizes();
		$sizesArray = explode(',', $sizeString);
		foreach($sizesArray as $ss) 
		{
			$s = explode(':', $ss);
			$sArray[] = $s[0];
			$qArray[] = $s[1];
			$tArray[] = $s[2];
		}
	} else
	{
		$sizesArray = array();
	}
	
	if($_POST) 
	{

		// Premier 'if() {]', pour le champ de prévisualisation des tailles et quanités, 
		$errors = array();
		

		//DEUXIEME 'if() {}', ON VERIFIE QUE LES CHAMPS DU FORMULAIRES D'AJOUT DE PRODUITS SONT VIDE? SI OUI ON REMPLI LE TABLEAU '$errors'
		$required = array('title', 'brand', 'price', 'parent', 'child', 'sizes');
		$allowed = array('png', 'jpg', 'jpeg', 'gif');
		$tmpLoc = array();
		$uploadPath = array();
		foreach($required as $field) 
		{
			if($_POST[$field] == '') 
			{
				$errors[] = 'All fields with an asterisk are required.';
				break;
			}
		}
		$photoCount = count($_FILES['photo']['name']);

		//VERIFICATION DE LIMAGE UPLOADER POUR LAJOUT DE PRODUIT
		if($photoCount > 0)
		{
			for($i = 0; $i < $photoCount; $i++)
			{
				$name = $_FILES['photo']['name'][$i];
				$nameArray = explode('.', $name);
				$fileName = $nameArray[0];
				$fileExt = $nameArray[1];
				$mime = explode('/', $_FILES['photo']['type'][$i]);
				$mimeType = $mime[0];
				$mimeExt = $mime[1];
				$tmpLoc[] = $_FILES['photo']['tmp_name'][$i];
				$fileSize = $_FILES['photo']['size'][$i];
				$uploadName = md5(microtime().$i).'.'.$fileExt;
				$uploadPath[] = BASEURL.'images/products/'.$uploadName;
				if($i != 0)
				{
					$dbpath .= ',';
				}
				$dbpath .= '/shaunta/images/products/'.$uploadName;

				if($mimeType != 'image')
				{
					$errors[] = 'The file must be an image.';
				}

				if(!in_array($fileExt, $allowed))
				{
					$errors[] = 'The photo extension must be a png, jpg, jpeg or gif.';
				}

				if($fileSize > 25000000) 
				{
					$errors[] = 'The file size must be under 25MB.';
				}

				if($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg'))
				{
					$errors[] = 'File extension does not match the file.';
				}
			}
		
		}

		//SI LE TABLEAU DERREURS NEST PAS VIDE? ON AFFICHE LES ERRUERS
		if(!empty($errors))
		{
			echo display_errors($errors);
		} else
		{
			if($photoCount > 0)
			{
				for($i = 0; $i < $photoCount; $i++)
				{
					//upload product photo and insertinto database
					move_uploaded_file($tmpLoc[$i], $uploadPath[$i]);
				}
			}
			$insertSql = "INSERT INTO products (title, price, list_price, brand, categories, image, description, featured, sizes, deleted) VALUES ('$title', '$price', '$list_price', '$brand', '$category', '$dbpath', '$description', '0', '$sizes', '0')";

			if(isset($_GET['edit']))
			{
				
					$insertSql = "UPDATE products 
						SET title = '$title', 
							price = '$price', 
							list_price = '$list_price',
							brand = '$brand',
							categories = '$category',
							image = '$dbpath',
							description = '$description',
							sizes = '$sizes'
						WHERE id = '$edit_id'";
			}

			$db->query($insertSql);
   			header('Location: products.php');   			
		}


	}

	while($b = mysqli_fetch_assoc($brand_query)) 
	{ 
		$brand_array[] = $b;
	} 

	while( $p = mysqli_fetch_assoc($parent_query)) 
	{
		$parent_array[] = $p;
	} 


				
		$imgi = 1;
		$images = explode(',', $saved_image);
		foreach($images as $image)
		{
			$image_array[] = $image;
		}

?>

<!-- *********** END ADD PRODUCT ******************** -->
<?php
/********************************************************************************************
****************************    LISTE PRODUIT   *********************************************
********************************************************************************************/
} else {

	$count_all = $db->query("SELECT COUNT(id) as 'count_all' FROM products WHERE deleted != 1");
	$count_all = mysqli_fetch_assoc($count_all);
	//PAGINATION VARIABLES
	$count_all = $count_all['count_all'];
	//juste a chager cette varialbe nombres darticle apr pages, tout le reste de fera automatiquement
	$per_page = 5;
	$current_page = 1;
	if(isset($_GET['p'])) {
		$current_page = sanitize($_GET['p']);
	}
	$page_numbers = ceil($count_all / $per_page);

	$down_limit = (($current_page -1) * $per_page);


	$products = "SELECT * FROM products WHERE deleted != 1 LIMIT ".$down_limit.", ".$per_page;
	$products = $db->query($products);
	if(isset($_GET['featured'])) {
		$featured_id = (int)$_GET['id'];
		$featured = (int)$_GET['featured'];
		$featured_sql = "UPDATE products SET featured = '$featured' WHERE id = '$featured_id'";
		$db->query($featured_sql);
		header('Location: products.php');
	}



while($product = mysqli_fetch_assoc($products)) { 

	$product_array[] = $product;

}

$count_array = count($product_array);

//echo $count_array;

for($i = 0; $i < $count_array; $i++)
{
	$category_id = $product_array[$i]['categories'];
	$category_id = (int)$category_id;
	$category = get_category($category_id);
	$category = $category['parent'].' ~ '. $category['child'];
	$product_array[$i]['category'] = $category;
}

//echo '<pre>';
//var_dump($product_array);
//echo '</pre>';
//	

	
}

//i need to make 1 array of sArray, qArray and tArray so i can loop on it with twig and get values
$size_qty_threshold_array = [];

for($i = 0; $i < 5; $i++)
{
	if(array_key_exists($i, $sArray))
	{
		$size_qty_threshold_array[$i] = [
			'size' => $sArray[$i],
			'qty' => $qArray[$i],
			'threshold' => $tArray[$i]
		];
	} else {
		$size_qty_threshold_array[$i] = [
			'size' => '',
			'qty' => '',
			'threshold' => ''
		];
	}
	
}

echo $twig->render('products.twig', [
		'product_array' => $product_array,
		'get_edit' => $get_edit,
		'get_add' => $get_add,
		'edit_id' => $edit_id,
		'title' => $title,
		'brand' => $brand,
		'brand_array' => $brand_array,
		'parent' => $parent,
		'parent_array' => $parent_array,
		'price' => $price,
		'list_price' => $list_price,
		'sizes' => $sizes,
		'saved_image' => $saved_image,
		'image_array' => $image_array,
		'description' => $description,
		'size_qty_threshold_array' => $size_qty_threshold_array,
		'page_numbers' => $page_numbers,
		'current_page' => $current_page
	]);	



//echo '<pre>';
//var_dump($size_qty_threshold_array);
//echo '</pre>';


include 'includes/footer.php';
?>
<script type="text/javascript">
	$('document').ready(function() {
		get_child_options('<?= $category; ?>');
		
	});
</script>
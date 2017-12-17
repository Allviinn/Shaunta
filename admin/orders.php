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

	
//complete order
if(isset($_GET['complete']) && $_GET['complete'] == 1)
{
	$cart_id = sanitize((int)$_GET['cart_id']);
	$db->query("UPDATE cart SET shipped = 1 WHERE id = '$cart_id'");
	$_SESSION['success_flash'] = "The order is completed";
	header('Location: index.php');
}


$txn_id = sanitize((int)$_GET['txn_id']);

$txnQuery = $db->query("SELECT * FROM transactions WHERE id = '$txn_id'");
$txn = mysqli_fetch_assoc($txnQuery);
$cart_id = $txn['cart_id'];

$cartQuery = $db->query("SELECT * FROM cart WHERE id = '$cart_id'");
$cart = mysqli_fetch_assoc($cartQuery);

$items = json_decode($cart['items'], true);
$idArray = array();
$products = array();

foreach($items as $item)
{
	$idArray[] = $item['id'];
}

$ids = implode(',', $idArray);

$productQ = $db->query("
			SELECT i.id as 'id', i.title as 'title', c.id as 'cid', c.category as 'child', p.category as 'parent'
			FROM products i 
			LEFT JOIN categories c ON i.categories = c.id
			LEFT JOIN categories p ON c.parent = p.id
			WHERE i.id IN ($ids)
	");

while($p = mysqli_fetch_assoc($productQ))
{
	foreach($items as $item)
	{
		if($item['id'] == $p['id'])
		{
			$x = $item;
			continue;
		}
	}

	$products[] = array_merge($x, $p);
}

echo $twig->render('orders.twig', [
		'products' => $products,
		'txn' => $txn
	]);


include 'includes/footer.php';
?>
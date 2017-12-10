<?php
require_once "../core/init.php";

if(!is_logged_in())
{
	header('Location: login.php');
}

include "includes/head.php";
include "includes/navigation.php";

//rednu du template
$loader = new Twig_Loader_Filesystem('templates_admin');
$twig = new Twig_Environment($loader, [
		'cache' => false
	]);

$twig->addExtension(new MonExtension());

?>


<!-- ORDERS TO FILL -->
<?php
$txnQuery = "	SELECT 
				t.id, 
				t.cart_id, 
				t.full_name, 
				t.description, 
				t.tctn_date, 
				t.grand_total, 
				c.items, 
				c.paid, 
				c.shipped 
				FROM transactions t
				LEFT JOIN cart c 
				ON t.cart_id = c.id
				WHERE c.paid = 1 
				AND c.shipped = 0
				ORDER BY t.tctn_date";

$txnResults = $db->query($txnQuery);
$orders = array();
while($order = mysqli_fetch_assoc($txnResults))
{
	$orders[] = $order;
}



?>


<div clas="row">
	<!-- SALES BY MONTH -->
	<?php
	
	$thisYr = date("Y");
	$lastYr = $thisYr - 1;
	$thisYrQ = $db->query("SELECT grand_total, tctn_date FROM transactions WHERE YEAR(tctn_date) = '$thisYr'");
	$lastYrQ = $db->query("SELECT grand_total, tctn_date FROM transactions WHERE YEAR(tctn_date) = '$lastYr'");
	
	$current = array();
	$last = array();
	$currentTotal = 0;
	$lastTotal = 0;

	//remplissage du tableau current. Il ne contient que les clefs des mois ou il y a eu des ventes, donc les clefs peuvent commencer à 6,11 ou 12.
	while($x = mysqli_fetch_assoc($thisYrQ))
	{
		$month = date("m", strtotime($x['tctn_date']));
		if(!array_key_exists($month, $current))
		{
			$current[(int)$month] = $x['grand_total'];
		}
		else
		{
			$current[(int)$month] += $x['grand_total'];
		}
	
		$currentTotal += $x['grand_total'];
	}

	//remplissage des clefs du tableau manquante en leur donnant la valeur 0
	for($i = 1; $i <= 12; $i++)
	{
		if(array_key_exists($i, $current))
		{

		}
		else
		{
			$current[$i] = floatval(0);
		}
	}
	//tri du tableau par clefs ASC
	ksort($current);


	//remplissage du tableau last. Il ne contient que les clefs des mois ou il y a eu des ventes, donc les clefs peuvent commencer à 6,11 ou 12.
	while($y = mysqli_fetch_assoc($lastYrQ))
	{
		$month = date("m", strtotime($y['tctn_date']));
		if(!array_key_exists($month, $last))
		{
			$last[(int)$month] = $y['grand_total'];
		}
		else
		{
			$last[(int)$month] += $y['grand_total'];
		}
	
		$lastTotal += $y['grand_total'];
	}


	//remplissage des clefs du tableau manquante en leur donnant la valeur 0
	for($i = 1; $i <= 12; $i++)
	{
		if(array_key_exists($i, $last))
		{
	
		}
		else
		{
			$last[$i] = floatval(0);
		}
	}
	//tri du tableau par clefs ASC
	ksort($last);


	//on combine les deux tableau (vente par lois de l'année derniere, et de cette anée pour boucler dessus avec twig)
	$merged_array = [];

	for($i = 1; $i <= 12; $i++)
	{
		$merged_array[$i] = [
			'lastYrMonth' => $last[$i],
			'currentYrMonth' => $current[$i]
		];
	}




	?>

	<!--Inventory -->
	<?php
	$iQuery = $db->query("SELECT * FROM products WHERE deleted = 0");
	$lowItems = array();
	while($product = mysqli_fetch_assoc($iQuery))
	{
		$item = array();
		$sizes = sizesToArray($product['sizes']);
		foreach($sizes as $size)
		{
			if($size['quantity'] <= $size['threshold'])
			{
				$cat = get_category($product['categories']);
				$item = array(
					'title' => $product['title'],
					'size' => $size['size'],
					'quantity' => $size['quantity'],
					'threshold' => $size['threshold'],
					'category' => $cat['parent'].' ~ '.$cat['child']
					);
				$lowItems[] = $item;
			}
		}
	}	


	echo $twig->render('index.twig', [
		'orders' => $orders, // les commandes à expedier
		'thisYr' => $thisYr, //cette année
		'lastYr' => $lastYr, // l'année dernière
		'merged_array' => $merged_array, //tableau contenant les somme par mois, pour cette année, et celle d'avant
		'currentTotal' => $currentTotal, // total des sommes de cette année
		'lastTotal' => $lastTotal,	//total des sommes de l'année dernière
		'lowItems' => $lowItems //les produits qui sont en dessous du seuil critique
	]);


include "includes/footer.php";
?>
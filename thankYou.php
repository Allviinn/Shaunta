<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/shaunta/core/init.php';
// Set your secret key: remember to change this to your live secret key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
require_once 'vendor/stripe/stripe-php/init.php';
\Stripe\Stripe::setApiKey(STRIPE_PRIVATE);

// Token is created using Checkout or Elements!
// Get the payment token ID submitted by the form:
$token = $_POST['stripeToken'];
$full_name = sanitize($_POST['full_name']);
$email = sanitize($_POST['email']);
$street = sanitize($_POST['street']);
$street2 = sanitize($_POST['street2']);
$city = sanitize($_POST['city']);
$city = sanitize($_POST['city']);
$state = sanitize($_POST['state']);
$zip_code = sanitize($_POST['zip_code']);
$country = sanitize($_POST['country']);
$tax = sanitize($_POST['tax']);
$sub_total = sanitize($_POST['sub_total']);
$grand_total = sanitize($_POST['grand_total']);
$cart_id = sanitize($_POST['cart_id']);
$description = sanitize($_POST['description']);
$charge_amount = number_format($grand_total,2) * 100;
$meta_data = array(
	"cart_id" 	=> $cart_id,
	"tax" 		=> $tax,
	"sub_total" => $sub_total
);


try {
	// Charge the user's card:
	$charge = \Stripe\Charge::create(array(
	  "amount" => $charge_amount,
	  "currency" => CURRENCY,
	  "description" => $description,
	  "source" => $token,
	  "metadata" => $meta_data
	));

	//adjust inventory
	$itemQ = $db->query("SELECT * FROM cart WHERE id = '$cart_id'");
	$iresults = mysqli_fetch_assoc($itemQ);
	$items = json_decode($iresults['items'], true);

	foreach($items as $item)
	{
		$newSizes = array();
		$item_id = $item['id'];
		$productQ = $db->query("SELECT sizes, sold FROM products WHERE id = '$item_id'");
		$product = mysqli_fetch_assoc($productQ);
		$sold = $product['sold'] + $item['quantity'];
		$sizes = sizesToArray($product['sizes']);
		foreach($sizes as $size)
		{
			if($size['size'] == $item['size'])
			{
				$q = $size['quantity'] - $item['quantity'];
				$newSizes[] = array('size' => $size['size'], 'quantity' => $q, 'threshold' => $size['threshold']);
			} 
			else 
			{
				$newSizes[] = array('size' => $size['size'], 'quantity' => $size['quantity'], 'threshold' => $size['threshold']);
			}
		}
		$sizeString = sizesToString($newSizes);
		$db->query("UPDATE products SET sizes = '$sizeString', sold = '$sold' WHERE id = '$item_id'");
	}

	//update cart
	$db->query("UPDATE cart SET paid = 1 WHERE id = '$cart_id'");
	$db->query("INSERT INTO transactions 
	(	charge_id, 
		cart_id, 
		full_name, 
		email, 
		street, 
		street2, 
		city, 
		state, 
		zip_code, 
		country, 
		sub_total, 
		tax, 
		grand_total, 
		description, 
		tctn_type) 
	VALUES 
	(	'$charge->id', 
		'$cart_id', 
		'$full_name', 
		'$email', 
		'$street', 
		'$street2', 
		'$city', 
		'$state', 
		'$zip_code', 
		'$country', 
		'$sub_total', 
		'$tax', 
		'$grand_total', 
		'$description', 
		'$charge->object')");

	 
	setcookie(CART_COOKIE, '', time()-3600, '/', false, false);
	include 'includes/head.php';
	include 'includes/navigation.php';
	include 'includes/headerpartial.php';
	?>
		<h1 class="text-center text-success">Thank You !</h1>
		<p>
			Your Cart has been successfully charged <?=money($grand_total);?>. You can print this page as a receipt.
		</p>

		<p>
			Your receipt number is <strong><?=$cart_id ;?></strong>
		</p>

		<p>
			Your order will be shipped to the address below.
		</p>

		<address>
			<?=$full_name ;?>
			<?=$street ;?>
			<?=(($street != '')?$street2.'<br>':'') ;?>
			<?=$city.','.$state.' '.$zip_code;?><br>
			<?=$country ;?><br>
		</address>

	<?php
	include 'includes/footer.php';

} catch(\Stripe\Error\Card $e) {
  // Since it's a decline, \Stripe\Error\Card will be caught
  $body = $e->getJsonBody();
  $err  = $body['error'];

  print('Status is:' . $e->getHttpStatus() . "\n");
  print('Type is:' . $err['type'] . "\n");
  print('Code is:' . $err['code'] . "\n");
  // param is '' in this case
  print('Param is:' . $err['param'] . "\n");
  print('Message is:' . $err['message'] . "\n");
}









?>
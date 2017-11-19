<?php

define('BASEURL', $_SERVER['DOCUMENT_ROOT'].'/e-commerce/');
define('CART_COOKIE', 'sLBI7Ff23EefhsgeGdg8');
define('CART_COOKIE_EXPIRE', time()+(86400 * 30));
define('TAXRATE', 0.20); //0.20 correspond à la TVA en France (20%), 10% = 0.1, 8.7% = 0.087.....

define('CURRENCY', 'eur');
define('CHECKOUTMODE', 'TEST');//change TEST to LIVE when ready to go live

if(CHECKOUTMODE == 'TEST')
{
	define('STRIPE_PRIVATE', 'sk_test_M0qfaPnNzku5duLN7TjhDTBA');
	define('STRIPE_PUBLIC', 'pk_test_LRflAeVIztvMKTjwpmAvPqOk');
}

//i dont have those live keys cuz my stripe account isn't activated
if(CHECKOUTMODE == 'LIVE')
{
	define('STRIPE_PRIVATE', '');
	define('STRIPE_PUBLIC', '');
}


?>
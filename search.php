<?php 
require_once "core/init.php"; //connection a la BDD
include "includes/head.php"; //head (DOCTYPE ....)
include "includes/navigation.php"; //menu de navigation
include "includes/headerpartial.php"; //image transform scale
include "includes/left_bar.php"; //bar a gauche du contenu pricipale

$sql = "SELECT * FROM products";

$cat_id = (($_POST['cat'] != "")?sanitize($_POST['cat']):'');

if($cat_id == "")
{
	$sql .= " WHERE deleted = 0";
}
else 
{
	$sql .= " WHERE categories = '$cat_id' AND deleted = 0";
}

$price_sort = (($_POST['price_sort'] != "")?sanitize($_POST['price_sort']):'');
$min_price = (($_POST['min_price'] != "")?sanitize($_POST['min_price']):'');
$max_price = (($_POST['max_price'] != "")?sanitize($_POST['max_price']):'');
$brand = (($_POST['brand'] != "")?sanitize($_POST['brand']):'');

if($min_price != "")
{
	$sql .= " AND price >= '$min_price'";
}

if($max_price != "")
{
	$sql .= " AND price <= '$max_price'";
}

if($brand != "")
{
	$sql .= " AND brand = '$brand'";
}

if($price_sort == 'low')
{
	$sql .= " ORDER BY price";
}

if($price_sort == 'high')
{
	$sql .= " ORDER BY price DESC";
}

$productQ = $db->query($sql);
$category = get_category($cat_id);

?>


<!-- CONTENU PRINCIPAL-->
<div class='col-md-8'>
	<div class="row">
	<?php if($cat_id != ""): ?>
			<h2 class="text-center"><?=$category['parent'].' '.$category['child'];?></h2>
	<?php else: ?>
		<h2 class="text-center">Shaunta's Botique</h2>
	<?php endif; ?>

	<?php while ($products = mysqli_fetch_assoc($productQ)) { ?>

		<div class="col-md-3 product-div">
			<h4 class="product_title text-center"><?=$products["title"];?></h4>
			<?php
				$photos = explode(',', $products['image']);
			?>
			<img src="<?=$photos[0];?>" alt="<?=$products['image'];?>" class="img-thumb center-block"/>
			<p class="list-price text-danger text-center">
				List Price: <s>$<?=$products["list_price"];?></s>
			</p>
			<p class="price text-center">
				Our Price : $<?=$products["price"]; ?>
			</p>
			<button type="button" class="btn btn-sm btn-success center-block" onclick="detailModal(<?= $products['id']; ?>)">
				Details
			</button>
		</div>

	<?php } ?>

	</div>
</div>




<?php

include "includes/right_bar.php"; //bar a droite du conteneu principal
include "includes/footer.php";//footer 

?>